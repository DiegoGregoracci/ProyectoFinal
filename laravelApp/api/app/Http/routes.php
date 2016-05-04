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

Route::get('/', function () {
    return view('welcome');
});
Route::post('/client/new', function(Request $request) {
	// Nuevo cliente
});
Route::get('/client/{clientId}', function(Client $client) {
	// Devuelve cliente especifico
});
Route::get('/client/search/{searchMethod}/{searchParam}', function (Client $client) {
	// Busqueda.
	// searchMethod -> para manejar distinto tipos de busqueda (por ID, por NOMBRE, por TODO, etc)
	// searchParam -> el parametro a buscar
});
Route::delete('/client/delete/{clientId}', function(Client $client) {
	// Borrar cliente
});