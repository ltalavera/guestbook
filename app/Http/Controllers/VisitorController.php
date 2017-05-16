<?php

namespace App\Http\Controllers;

use App\BranchOffice;
use App\Http\Requests\VisitorRequest;
use App\Transformer\VisitorTransformer;
use App\Visitor;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use JWTAuth;

class VisitorController extends Controller
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
    public function index($branch_office_id)
    {
        if ($user = JWTAuth::parseToken()->authenticate()) {
            if ($user->hasRole('admin')) {
                $items = BranchOffice::findOrFail($branch_office_id)->visitors()->withTrashed()->paginate(15);
                return $this->response->withPaginator($items, new VisitorTransformer());
            } else {
                $items = BranchOffice::findOrFail($branch_office_id)->visitors()->paginate(15);
                return $this->response->withPaginator($items, new VisitorTransformer());
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VisitorRequest $request)
    {
        // Creates the new object
        $item = new Visitor;

        // Set values obtained from the json
        $item->document_id = $request->document_id;
        $item->full_name = $request->full_name;
        $item->leader_full_name = $request->leader_full_name;

        if ($item->save()) {
            return $this->response->withItem($item, new  VisitorTransformer());
        } else {
            return $this->response->errorInternalError('There was an error trying to save the new visitor');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        // Find the visitor
        $item = Visitor::findOrFail($id);

        if ($item->update($request->all())) {
            return $this->response->withItem($item, new  VisitorTransformer());
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
        $item = Visitor::findOrFail($id);

        if ($item->delete()) {
            return $this->response->withItem($item, new VisitorTransformer());
        } else {
            return $this->response->errorInternalError('Could not delete the item');
        }
    }
}
