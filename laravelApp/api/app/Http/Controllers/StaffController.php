<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Staff;
use App\Client;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
// Include validators.php to extend Validators
require app_path().'/Validators.php';
// Include constantes
require app_path().'/Constants.php';
use DB;
use Illuminate\Database\DatabaseManager;

/*
    TO DO LIST:

        - Manejar errores comunes con constantes.

*/

class StaffController extends Controller
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
            'lastname'      => 'required|alpha_spaces|between:3,20',
            'name'          => 'required|alpha_spaces|between:3,20',
            'address'       => 'max:30|alpha_num_spaces',
            'telephone'    => 'max:15|alpha_num_spaces',
            'email'         => 'email|max:30'
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('name'))
                $response[] = array(        "error"  => VALIDATOR_NAME_ALPHA      );
            if ($messages->has('lastname'))
                $response[] = array(        "error"  => VALIDATOR_LASTNAME_ALPHA  );
            if ($messages->has('address'))
                $response[] = array(        "error"  => VALIDATOR_ADDRESS   );
            if ($messages->has('telephone'))
                $response[] = array(        "error"  => VALIDATOR_TELEPHONE);
            if ($messages->has('email'))
                $response[] = array(        "error"  => VALIDATOR_EMAIL     ); 
            return response()->json($response);
        }
        
        try{
            DB::beginTransaction();
            // Asigno los parametros
            $staff = new Staff();
            $staff->lastname = $request->lastname;
            $staff->name = $request->name;
            $staff->address = $request->address;
            $staff->telephone = $request->telephone1;
            $staff->email = $request->email;
            $staff->save();
            DB::commit();
            return response()->json(["id"=>$staff->id]);
        }
        catch (\Exception $e) {
            // Hubo error agregando
            // Solo puede error de conexión en este punto, ya que los datos ya están validados y no hay ningún campo UNIQUE.
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>QUERY_CONN);
                else {
                // Agarro cualquier otro error por las dudas
                DB::rollback();
                $response[] = array("error"=>QUERY_UNEXPECTED);
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
            // Busca staff. El ->first() es necesario ya que el select devuelve una colección de resultados.
            // Con ->first() obtiene el primer (y unico en este caso) elemento, y a partir de ahi podemos llamar a funciones del modelo
            $staff = Staff::select('id', 'name', 'lastname', 'address', 'telephone', 'email')
                            ->where('id', $id)
                            ->get()
                            ->first();
            
            if (is_null($staff))
                // Si el cliente es nulo, es porque no existe.
                return response()->json(array("error" =>  QUERY_NOTEXISTINGSTAFF));

            return response()->json($staff);
        }
        catch (\Exception $e) {
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. 
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response = array("error"=>QUERY_CONN);
            else
                $response = array("error"=>QUERY_UNEXPECTED);
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
            'lastname'      => 'required|alpha_spaces|between:3,20',
            'name'          => 'required|alpha_spaces|between:3,20',
            'address'       => 'max:30|alpha_num_spaces',
            'telephone'    => 'max:15|alpha_num_spaces',
            'email'         => 'email|max:30'
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('name'))
                $response[] = array(        "error"  => VALIDATOR_NAME_ALPHA      );
            if ($messages->has('lastname'))
                $response[] = array(        "error"  => VALIDATOR_LASTNAME_ALPHA  );
            if ($messages->has('address'))
                $response[] = array(        "error"  => VALIDATOR_ADDRESS   );
            if ($messages->has('telephone'))
                $response[] = array(        "error"  => VALIDATOR_TELEPHONE);
            if ($messages->has('email'))
                $response[] = array(        "error"  => VALIDATOR_EMAIL     ); 
            return response()->json($response);
        }

        try {
            // Buscar staff
            $staff = Staff::find($id);
        } 
        catch (\Exception $e) {
            // Hubo error buscando
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response[] = array("error"=>QUERY_CONN);
            else
                $response[] = array("error"=>QUERY_UNEXPECTED);
            return response()->json($response);
        }

        if (is_null($staff))
            // Si no se pudo obtener el cliente
            return response()->json(["error"=>QUERY_NOTEXISTINGSTAFF]);
        else{

            try{
                DB::beginTransaction();
                // Asigno los parametros
                $staff->lastname = $request->lastname;
                $staff->name = $request->name;
                $staff->address = $request->address;
                $staff->telephone = $request->telephone1;
                $staff->email = $request->email;
                $staff->save();
                DB::commit();
                return response()->json(["id"=>$staff->id]);
            }
            catch (\Exception $e) {
                // Hubo error agregando
                // Solo puede error de conexión en este punto, ya que los datos ya están validados y no hay ningún campo UNIQUE.
                $errorCode = $e->getCode();
                if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                    // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                    // Si es 1044, usuario incorrecto
                    // Si es 1049, no existe la tabla
                    $response[] = array("error"=>QUERY_CONN);
                    else {
                    // Agarro cualquier otro error por las dudas
                    DB::rollback();
                    $response[] = array("error"=>QUERY_UNEXPECTED);
                    }
                return response()->json($response);
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
        //Definir SOFT delete por campo ACTIVO
    }

    /**
     * Search staff.
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
            return response()->json([   "error"     =>  VALIDATOR_SEARCH    ]);
            
        try {
            $staff = Staff::select('id', 'lastname', 'name')
                            ->where('name', "LIKE", "%".$param."%")
                            ->orWhere('lastname', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $staff;
        }
        catch (\Exception $e) {
            // Hubo error buscando.
            // Solo puede error de conexión en este punto.
            $errorCode = $e->getCode();
            if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                // Si es 2002, es porque no se pudo conectar. 
                // Si es 1044, usuario incorrecto
                // Si es 1049, no existe la tabla
                $response = array("error"=>QUERY_CONN);
            else
                // Agarro cualquier otro error por las dudas
                $response = array("error"=>QUERY_UNEXPECTED);
            return response()->json($response);
        }
    }
}