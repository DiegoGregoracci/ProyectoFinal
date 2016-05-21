<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Client;
use App\User;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Database\DatabaseManager;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "index";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar
        $validator = Validator::make($request->all(), [
            'user' => 'required|max:20',
            'lastname' => 'required|max:30',
            'name' => 'required|max:30',
            'address' => 'max:30'
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) {
            $messages = $validator->messages();
            if ($messages->has('user'))
                return "user incorrecto";
            if ($messages->has('address'))
                return "direccion incorrecta";   
            return "datos incorrectos";
        }

        // Crear nuevo usuario
        $user = new User;
        $user->user = $request->user;
        $user->password = "test";

        try {
            // Inicio transaccion e intento guardar el usuario
            DB::beginTransaction();
            $user->save();
        }
        catch (\Exception $e) {
            // Hubo error agregando
            $errorCode = $e->getCode();
            if ($errorCode == 23000) {
                // Si es 23000 el codigo, es porque hay violacion de integridad. Habiendo validado los campos requeridos arriba,
                // Supongo que lo unico que puede pasar es que el username exista, y al ser unique tira error
                DB::rollback();
                return "entrada duplicada, pedir que cambie el username";
            }
            else
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    return "error de conexion";
                else {
                    // Agarro cualquier otro error por las dudas
                    DB::rollback();
                    return "error ".$errorCode." ".$e->getMessage();;
                }
        }

        // Completo los datos del cliente
        // Si llego a este punto, es porque ya se agrego el usuario
        $client = new Client;
        $client->lastname   = $request->lastname;
        $client->name       = $request->name;
        $client->address    = $request->address;
        $client->telephone1 = $request->telephone1;
        $client->telephone2 = $request->telephone2;
        $client->email      = $request->email;
        $client->cuit       = $request->cuit;

        // Pregunto si se le asigno ID a user. No se si es necesario, es una validacion extra para saber si se agrego el usuario
        if ($user->id) {
            // Le asigno el ID del usuario creado para usarlo de FK
            $client->id_user = $user->id;
            try {
                // Intento guardar el cliente
                $client->save();
                DB::commit();
                return "agregado!";
            }
            catch (\Exception $e) {
                // Hubo error guardando al cliente
                $errorCode = $e->getCode();
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    return "error de conexion";
                else {
                    // Si ocurrio un error, hago rollback y salgo.
                    // No se que error puede llegar a haber. Los campos NOTNULL ya se comprueban en el validator.
                    DB::rollback();
                    return "error ".$errorCode." ".$e->getMessage();;
                }
            }
        }
        else {
            DB::rollback();
            return "error creando al cliente";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "prueba";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return "lalalal";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
