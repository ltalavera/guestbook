<?php

namespace App\Http\Controllers;

use App\EntryLog;
use App\Http\Requests\EntryLogRequest;
use App\Transformer\EntryLogTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class EntryLogController extends Controller
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
    public function index($entry_id) {
        $items = EntryLog::where('entry_id', '=', $entry_id)->paginate(15);
        return $this->response->withPaginator($items, new EntryLogTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($entry_id, EntryLogRequest $request)
    {   
        // Creates the new object
        $item = new EntryLog;

        // Entry Id is part of the query URL
        $item->entry_id = $entry_id;

        // Other values obtained from the json
        $item->performed_at = $request->performed_at;
        $item->performed_by = $request->performed_by;
        if ($request->requested_data) {
            $item->requested_data = preg_replace("/[\n\r]/", "", $request->requested_data);
        }

        if ($item->save()) {
            return $this->response->withItem($item, new  EntryLogTransformer());
        } else {
            return $this->response->errorInternalError('There was an error trying to save the new entry log');
        }
    }

    /**
     * Update the specified resource in storage.
     * @param  int  $entry_id The id of the entry
     * @param  int  $id The id of the log
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($entry_id, $id, Request $request)
    {
        // Find the entry
        $item = EntryLog::findOrFail($id);

        if ($item->update($request->all())) {
            return $this->response->withItem($item, new  EntryLogTransformer());
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
        $item = EntryLog::findOrFail($id);

        if ($item->delete()) {
            return $this->response->withItem($item, new EntryLogTransformer());
        } else {
            return $this->response->errorInternalError('Could not delete the item');
        }
    }
}
