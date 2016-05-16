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
Route::get('/client/{clientId}', function(Client $client) {
	// Devuelve cliente especifico
	echo "lala";
});
Route::get('/client/search/{searchMethod}/{searchParam}', function (Client $client) {
	// Busqueda.
	// searchMethod -> para manejar distinto tipos de busqueda (por ID, por NOMBRE, por TODO, etc)
	// searchParam -> el parametro a buscar
});
Route::delete('/client/delete/{clientId}', function(Client $client) {
	// Borrar cliente
});