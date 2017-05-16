<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use EllipseSynergie\ApiResponse\Contracts\Response;
use App\User;
use JWTAuth;
use App\Transformer\UserTransformer;

class UserController extends Controller {
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index() {
        return User::all();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function create(Request $request)
    {
        $user = new User;
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->password = bcrypt($request->input("password"));

        if($user->save()) {
            return $this->response->withItem($user, new  UserTransformer());
        } else {
            return $this->response->errorInternalError('Could not updated/created a user');
        }
    }

    /**
     * Updates the user attributes (e.g. email, password)
     * @param  [type]  $id      The id of the record
     * @param  Request $request The data that will be updated
     */
    public function update($id, Request $request) 
    {
        // Find the entry
        $item = User::findOrFail($id);

        if ($item->update($request->all())) {
            return $this->response->withItem($item, new  UserTransformer());
        } else {
            return $this->response->errorInternalError('There was an error trying to save the update');
        }
    }
}
