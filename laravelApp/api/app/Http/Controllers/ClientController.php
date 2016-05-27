<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Client;
use App\User;
use App\Vehicle;
use Illuminate\Support\Facades\Input;
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
        //
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
            'user'          => 'required|alpha_num|between:3,20',
            'lastname'      => 'required|alpha_spaces|between:3,20',
            'name'          => 'required|alpha_spaces|between:3,20',
            'address'       => 'max:30|alpha_num_spaces',
            'telephone1'    => 'max:15|alpha_num_spaces',
            'telephone2'    => 'max:15|alpha_num_spaces',
            'email'         => 'email|max:30'
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
            if ($messages->has('telephone2'))
                $response[] = array("error"=>"El teléfono 2 puede tener un máximo de 15 caractéres alfanuméricos.");  
            if ($messages->has('email'))
                $response[] = array("error"=>"El correo electrónico debe tener formato de e-mail. y hasta 30 caractéres."); 
            return response()->json($response);
        }

        // Crear nuevo usuario
        $user = new User;
        $user->user = $request->user;
        // Contraseña aleatoria de 8 caracteres
        $user->password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"),0,8);

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
            }
            else
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    $response[] = array("error"=>"Error de conexión a la base de datos.");
                else
                    // Agarro cualquier otro error por si hay alguno que se haya pasado por alto.
                    $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
            return response()->json($response);
        }

        // Asigno los parametros
        $client = new Client();
        $client->lastname = $request->lastname;
        $client->name = $request->name;
        $client->address = $request->address;
        $client->telephone1 = $request->telephone1;
        $client->telephone2 = $request->telephone2;
        $client->email = $request->email;
        $client->cuit = $request->cuit;
        $client->comments = $request->comments;
        
        try{
            // Creo el cliente utilizando la relación 
            $user->client()->save($client);
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
        try {
            // Busca cliente. El ->first() es necesario ya que el select devuelve una colección de resultados.
            // Con ->first() obtiene el primer (y unico en este caso) elemento, y a partir de ahi podemos llamar a funciones del modelo
            $client = Client::select('clients.id', 'name', 'lastname', 'address', 'telephone1', 'telephone2', 'email', 'cuit', 'users.user')
                            ->join('users', 'id_user', '=', 'users.id')
                            ->where('clients.id', $id)
                            ->get()
                            ->first();
            
            if (!is_null($client))
                // Buscar vehículos relacionados si el cliente no es nulo.
                $vehicles = $client->vehicles()->select('id', 'brand', 'model', 'plate')->get();
            else
                // Si el cliente es nulo, es porque no existe.
                return response()->json(array("error" =>  "Cliente inexistente"));

            $response = array(
                                "client"    =>  $client,
                                "vehicles"  =>  $vehicles
                             );
            return response()->json($response);
        }
        catch (\Exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. 
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>"Error de conexión a la base de datos.");
            else
                $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
            return response()->json($response);
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
        //
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
        // Validar
        $validator = Validator::make($request->all(), [
            'user'          => 'required|alpha_num|between:3,20',
            'lastname'      => 'required|alpha_spaces|between:3,20',
            'name'          => 'required|alpha_spaces|between:3,20',
            'address'       => 'max:30|alpha_num_spaces',
            'telephone1'    => 'max:15|alpha_num_spaces',
            'telephone2'    => 'max:15|alpha_num_spaces',
            'email'         => 'email|max:30'
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
            if ($messages->has('telephone2'))
                $response[] = array("error"=>"El teléfono 2 puede tener un máximo de 15 caractéres alfanuméricos.");  
            if ($messages->has('email'))
                $response[] = array("error"=>"El correo electrónico debe tener formato de e-mail. y hasta 30 caractéres."); 
            return response()->json($response);
        }

        try {
            // Buscar cliente
            $client = Client::find($id);
        }
        catch (\Exception $e) {
            // Hubo error buscando
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>"Error de conexión a la base de datos.");
            else
                $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
            return response()->json($response);
        }

        if (is_null($client))
            // Si no se pudo obtener el cliente
            return response()->json(["error"=>"El cliente no existe."]);
        else {
            try {
                // Buscar el usuario
                $user = $client->user()->get()->first();
            }
            catch (\Exception $e) {
                // Hubo error buscando
                $errorCode = $e->getCode();
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    $response[] = array("error"=>"Error de conexión a la base de datos.");
                else
                    $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
                return response()->json($response);
            }

            // Si llegó aca, ya buscó el cliente y el usuario.
            if (is_null($user))
                // No existe el usuario (esto no debería pasar nunca).
                return response()->json(["error"=>"El usuario no existe."]);
            else {
                // Encontro el usuario y el cliente.
                // Primero se actualiza el username, y despues el cliente.       
                $user->user = $request->user;
                try {
                    // Inicio transaccion e intento guardar el usuario
                    DB::beginTransaction();
                    $user->save();
                }
                catch (\Exception $e) {
                    // Hubo error guardando
                    $errorCode = $e->getCode();
                    if ($errorCode == 23000) {
                        // Username duplicado
                        DB::rollback();
                        $response[] = array("error"=>"El nombre de usuario elegido ya existe en la base de datos.");
                    }
                    else
                        if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                            // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                            // Si es 1044, usuario incorrecto
                            // Si es 1049, no existe la tabla
                            $response[] = array("error"=>"Error de conexión a la base de datos.");
                    return response()->json($response);
                }

                // Se actualizó el usuario. Paso a actualizar el cliente.
                // Asigno nuevos parametros.
                $client->lastname = $request->lastname;
                $client->name = $request->name;
                $client->address = $request->address;
                $client->telephone1 = $request->telephone1;
                $client->telephone2 = $request->telephone2;
                $client->email = $request->email;
                $client->cuit = $request->cuit;
                $client->comments = $request->comments;
                
                try{
                    // Guardo cambios en el cliente
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
                        // Agarro cualquier otro error por las dudas y hago rollback para no guardar ningún cambio.
                        DB::rollback();
                        $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
                    }
                    return response()->json($response);
                }
            }
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
        // No está listo porque falta definir cómo hacemos los DELETE (soft delete)
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

    /**
     * Search clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function search($param = null)
    {
        // Validar
        $validator = Validator::make(
            array("searchParam"=>$param), [
            'searchParam' => 'required|alpha_num_spaces|max:20'
        ]);
        if ($validator->fails())
            return response()->json(["error"=>"El parámetro de búsqueda solo puede contener letras, números y espacios."]);
            
        try {
            $user = Client::select('id', 'lastname', 'name')
                            ->where('name', "LIKE", "%".$param."%")
                            ->orWhere('lastname', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $user;
        }
        catch (\Exception $e) {
            // Hubo error buscando.
            // Solo puede error de conexión en este punto.
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. 
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>"Error de conexión a la base de datos.");
            else
                // Agarro cualquier otro error por las dudas
                $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
            return response()->json($response);
        }
    }
}