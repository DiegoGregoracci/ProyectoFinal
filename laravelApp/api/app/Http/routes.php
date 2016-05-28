<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Client;
use App\User;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

// Extender funciones de los resource controller
Route::get('client/search/{param}', 'ClientController@search');
Route::get('vehicle/search/{param}', 'VehicleController@search');

Route::resource('client', 'ClientController');
Route::resource('vehicle', 'VehicleController');
Route::resource('supplier', 'SupplierController');