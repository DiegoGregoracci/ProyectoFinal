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


Route::resource('client', 'ClientController');
Route::resource('vehicle', 'VehicleController');

/*
Route::post('/client/new', function(Request $request) {
	// Nuevo cliente
	$user = new User;
	$user->user = $request->usuario;
	$user->password = "test";
	try {
		$user->save();
	}
	catch (\Illuminate\Database\QueryException $e) {
		echo "error creando el usuario";
	}
	if ($user->id) {
		$client = new Client;
		$client->name = $request->name;
		$client->id_user = $user->id;
		echo $user->id;
		$client->save();
		if ($client->id) 
			echo "agregado";
		else {
			$user->delete();
			echo "error: se borra usuario";
		}
	}
});
*/