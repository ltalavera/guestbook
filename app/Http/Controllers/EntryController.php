<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Entry;
use App\EntryLog;
use App\GuestType;
use App\Http\Requests\EntryRequest;
use App\Transformer\EntryTransformer;
use App\Visitor;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JWTAuth, Auth, Log, Exception;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;

class EntryController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($branchOfficeId)
    {
        // Get the users
        $user = Auth::User();
        if ($user->hasRole('admin')) {
          $items = Entry::withTrashed()->where('branch_office_id', '=', $branchOfficeId)->orderBy('entry_in', 'desc')->paginate(15);
        } else {
          $items = Entry::where('branch_office_id', '=', $branchOfficeId)->orderBy('entry_in', 'desc')->paginate(15);
        }
        return $this->response->withPaginator($items, new EntryTransformer());
    }

    /**
     * Display a listing of the resource with some filters.
     * @param branchOfficeId The id of the office
     * @param request The request filters
     * @return \Illuminate\Http\Response
     */
    public function query($branchOfficeId, Request $request)
    {
        // Get the users
        $user = Auth::User();

        // Create the request depending on the role
        if ($user->hasRole('admin')) {  
          $query = Entry::withTrashed();

          if ($request->branch_offices) {
            $query = $query->whereIn('branch_office_id', explode(',', $request->branch_offices));
          } else {
            $query = $query->where('branch_office_id', '=', $branchOfficeId);
          }

        } else {
          $query = Entry::where('branch_office_id', '=', $branchOfficeId);
        }

        // Add a start date to the query
        if ($request->date_from) {
          $query = $query->where('entry_in', '>=', $request->date_from); 
        } 

        // Add a final date to the query
        if ($request->date_to) {
          $dt = Carbon::parse($request->date_to);  
          $query = $query->where('entry_in', '<=', $dt->addDay()->format('Y-m-d H:i:s'));
        }

        // Add different visitor types to the query
        if ($request->visitor_types) {
          $query = $query->whereIn('guest_type_id', explode(',', $request->visitor_types));
        }

        // Add the visitor's document id to the query
        if ($request->document_id) {
          $query = $query->where('guest_document_id', 'like', $request->document_id);
        }

        // Add the visitor's document id to the query
        if ($request->full_name) {
          $query = $query->where('guest_full_name', 'like', $request->full_name);
        }

        $items = $query->orderBy('entry_in', 'desc')->paginate(15); 

        return $this->response->withPaginator($items, new EntryTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($branchOfficeId, EntryRequest $request)
    {
        // Get the visitors document id
        $guest_document_id = $request->guest_document_id;

        // Creates the new visitor if it doesn't exist already
        $visitor = Visitor::where('document_id', '=' , $guest_document_id)->first();
        if (is_null($visitor)) {
            $visitor = new Visitor; 
            $visitor->document_id = $guest_document_id;
            $visitor->full_name = $request->guest_full_name;
            $visitor->leader_full_name = $request->leader_full_name;
            $visitor->save();
        }

        $item = new Entry;
        $item->id = Uuid::uuid4();
        $item->visitor_id = $visitor->id;

        // Branch Office Id is part of the query URL
        $item->branch_office_id = $branchOfficeId;

        // Other required fields
        $item->guest_type_id = $request->guest_type_id;
        $item->guest_document_id = $guest_document_id;
        $item->guest_full_name = $request->guest_full_name;
        $item->guest_signature = $request->guest_signature;
        $item->entry_in = Carbon::parse($request->entry_in)->format('Y-m-d H:i:s');

        // Optional fields
        $item->leader_full_name = $request->leader_full_name;
        if ($item->entry_out) {
            $item->entry_out = Carbon::parse($request->entry_out)->format('Y-m-d H:i:s');
        }

        // Validate the unique constraint, otherwise return the entry
        $entry = Entry::where('branch_office_id', '=', $item->branch_office_id)
        ->where('guest_document_id', '=', $item->guest_document_id)
        ->where('entry_in', '=', $item->entry_in)->get();

        if (!$entry->isEmpty()) {
          return $this->response->withItem($entry->first(), new  EntryTransformer());
        }

        if ($item->save()) {
            // Register the new Entry log
            $this->logForUpdate($item->id, $item->updated_at, $request);

            return $this->response->withItem($item, new  EntryTransformer());
        } else {
            return $this->response->errorInternalError('There was an error trying to save the new entry');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($branch_office_id, $id, Request $request)
    {
        // Find the entry
        $item = Entry::findOrFail($id);

        if ($item->update($request->all())) {
            // Register the new Entry log
            $this->logForUpdate($item->id, $item->updated_at, $request);

            return $this->response->withItem($item, new  EntryTransformer());
        } else {
            return $this->response->errorInternalError('There was an error trying to save the update');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Entry::findOrFail($id);

        if ($item->delete()) {
            // Register the new Entry log
            $this->logForUpdate($id, $item->updated_at, null);

            return $this->response->withItem($item, new EntryTransformer());
        } else {
            return $this->response->errorInternalError('Could not delete the item');
        }
    }

    /**
     * Creates an EntryLog for every modification
     * @param  entryId The Id of the entry
     * @param  performedAt The datetime when the modification took place
     * @param  request Some relevant information
     * @return boolean If the log was successfully saved
     */
    private function logForUpdate($entryId, $performedAt, $request)
    {
        // Get the user
        $user = Auth::User();

        // Verify the type of request
        if (!is_null($request)) {
            // Process the signature, since it has too much data
            $requestedData = $request->except('guest_signature');
            if ($request->guest_signature) {
                $signature = str_limit($request->guest_signature, $limit = 10, $end = '...');
                $requestedData = array_add($requestedData, 'guest_signature', $signature);
            }
        } 

        $item = new EntryLog;
        $item->entry_id = $entryId;
        $item->performed_at = $performedAt;
        $item->performed_by = $user->id;
        
        if (isset($requestedData)) {
            $item->requested_data = collect($requestedData)->toJson();
        }
        $item->save();
    }
}
