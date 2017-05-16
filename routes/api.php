<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return view('welcome');
});

////////////////////////////////////////////////////////////////////////////////////////
// Security
Route::post('role', 'JwtAuthenticateController@createRole');
Route::post('permission', 'JwtAuthenticateController@createPermission');
Route::post('assign-role', 'JwtAuthenticateController@assignRole');
Route::post('attach-permission', 'JwtAuthenticateController@attachPermission');
Route::post('check', 'JwtAuthenticateController@checkRoles');

Route::group(['prefix' => 'api', 'middleware' => ['ability:admin,create-users']], function()
{
    Route::get('users', 'JwtAuthenticateController@index');
});

Route::post('users/login', 'JwtAuthenticateController@authenticate'); 
Route::post('user/register', 'UserController@create');

Route::group(['middleware' => ['before' => 'jwt.auth']], function() 
{
	////////////////////////////////////////////////////////////////////////////////////////
	// Users
	Route::get('users/me', 'JwtAuthenticateController@getAuthenticatedUser');	
	Route::get('users', 'UserController@index');
	Route::put('user/{id}', 'UserController@update');

	////////////////////////////////////////////////////////////////////////////////////////
	// Branches Offices
	Route::get('offices', 'BranchOfficeController@index');

	////////////////////////////////////////////////////////////////////////////////////////
	// Guest Types
	Route::get('guests_types', 'GuestTypeController@index');

	////////////////////////////////////////////////////////////////////////////////////////
	// Entries
	Route::get('office/{branch_office_id}/entries', 'EntryController@index');
	Route::post('office/{branch_office_id}/entries', 'EntryController@query');
	Route::post('office/{branch_office_id}/entry', 'EntryController@store');
	Route::put('office/{branch_office_id}/entry/{id}', 'EntryController@update');
	Route::delete('entry/{id}', 'EntryController@destroy');

	////////////////////////////////////////////////////////////////////////////////////////
	// Entry Logs
	Route::get('entry/{entry_id}/logs', 'EntryLogController@index');
	Route::post('entry/{entry_id}/log', 'EntryLogController@store');
	Route::put('entry/{entry_id}/log/{id}', 'EntryLogController@update');
	Route::delete('log/{id}', 'EntryLogController@destroy');

	////////////////////////////////////////////////////////////////////////////////////////
	// Visitors
	Route::get('office/{branch_office_id}/visitors', 'VisitorController@index');
	Route::post('visitor', 'VisitorController@store');
	Route::put('visitor/{id}', 'VisitorController@update');
	Route::delete('visitor/{id}', 'VisitorController@destroy');
});

