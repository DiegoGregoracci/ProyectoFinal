<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Client;
use App\User;
use Illuminate\Support\Facades\Validator;
// Include validators.php to extend Validators
require app_path().'/validators.php';
use DB;
use Illuminate\Database\DatabaseManager;

/*
    TO DO LIST:

        - Manejar errores comunes con constantes.

*/

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
            'user' => 'required|alpha_num|between:3,20',
            'lastname' => 'required|alpha_spaces|between:3,20',
            'name' => 'required|alpha_spaces|between:3,20',
            'address' => 'max:30|alpha_num_spaces',
            'telephone1' => 'max:15|alpha_num_spaces',
            'telephone2' => 'max:15|alpha_num_spaces',
            'email' => 'email|max:30'
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('user'))
                $response[] = array("error"=>"El nombre de usuario debe tener entre 3 y 20 caractéres alfanuméricos sin espacios.");
            if ($messages->has('name'))
                $response[] = array("error"=>"El nombre debe tener entre 3 y 20 caractéres alfanuméricos.");
            if ($messages->has('lastname'))
                $response[] = array("error"=>"El apellido debe tener entre 3 y 20 caractéres alfanuméricos.");
            if ($messages->has('address'))
                $response[] = array("error"=>"La dirección puede tener un máximo de 20 caractéres alfanuméricos.");
            if ($messages->has('telephone1'))
                $response[] = array("error"=>"El teléfono 1 puede tener un máximo de 15 caractéres alfanuméricos.");
            if ($messages->has('telephone'))
                $response[] = array("error"=>"El teléfono 2 puede tener un máximo de 15 caractéres alfanuméricos.");  
            if ($messages->has('email'))
                $response[] = array("error"=>"El correo electrónico debe tener formato de e-mail. y hasta 30 caractéres."); 
            return response()->json($response);
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
                $response[] = array("error"=>"El nombre de usuario elegido ya existe en la base de datos.");
                return response()->json($response);
            }
            else
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    $response[] = array("error"=>"Error de conexión a la base de datos.");
                    return response()->json($response);
        }

        $client = new Client();
        $client->id_user = $user->id;
        $client->lastname = $request->lastname;
        $client->name = $request->name;
        $client->address = $request->address;
        $client->telephone1 = $request->telephone1;
        $client->telephone2 = $request->telephone2;
        $client->email = $request->email;
        $client->cuit = $request->cuit;
        $client->comments = $request->comments;
        
        try{
            $client->save();
            DB::commit();
            return response()->json(["id"=>$client->id]);
        }
        catch (\Exception $e) {
            // Hubo error agregando
            // Solo puede error de conexión en este punto, ya que los datos ya están validados y no hay ningún campo UNIQUE.
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>"Error de conexión a la base de datos.");
                else {
                // Agarro cualquier otro error por las dudas
                DB::rollback();
                $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
            }
            return response()->json($response);
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
        try{
            $user = Client::where('id', $id)->get();
            return $user;
        }
        catch (\Exception $e) {
            return "error al buscar cliente";
        }
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
        $user = Client::where('id', $id)->get();

        $client = new Client();
        $client->lastname = $request->lastname;
        $client->name = $request->name;
        $client->adress = $request->adress;
        $client->tel1 = $request->tel1;
        $client->tel2 = $request->tel2;
        $client->email = $request->email;
        $client->cuit = $request->cuit;
        $client->comments = $request->comments;
        try{
            $client->save();
        }
        catch (\Exception $e) {
            return "error al actualizar cliente";
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
        $client = Client::where('id', $id)->get();
        $user = User::where('id', $client->id_user)->get();
        try{
            $user->delete();
            $client->delete(); 
        }
        catch (\Exception $e) {
            return "error al eliminar cliente";
        }
    }
}
