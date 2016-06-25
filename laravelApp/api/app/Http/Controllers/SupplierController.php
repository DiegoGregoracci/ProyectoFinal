<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Supplier;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;
use Illuminate\Database\DatabaseManager;
// Include validators.php to extend Validators
require app_path().'/Validators.php';
// Include constantes
require app_path().'/Constants.php';
class SupplierController extends Controller
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
            'razon' => 'required|max:50',
            'telephone' => 'required|max:15|numeric',
            'adress' => 'required|max:30|alpha_num_spaces',
            'email' => 'email|max:30',
            'responsible' => 'required|max:20|alpha_spaces',
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('razon'))
                $response[] = array(        "error" =>  VALIDATOR_DESCRIPTION);
            if ($messages->has('telephone'))
                $response[] = array(        "error" =>  VALIDATOR_TEL);
            if ($messages->has('adress'))
                $response[] = array(        "error" =>  VALIDATOR_ADRESS);
            if ($messages->has('email'))
                $response[] = array(        "error" =>  VALIDATOR_EMAIL);
            if ($messages->has('responsible'))
                $response[] = array(        "error" =>  VALIDATOR_RESPONSIBLE);
            return response()->json($response);
        }

        $supplier = new Supplier();
        $supplier->description = $request->razon;
        $supplier->tel = $request->telephone;
        $supplier->adress = $request->adress;
        $supplier->email = $request->email;
        $supplier->responsible = $request->responsible;

        try{
        	$supplier->save();
        	return response()->json(["id"=>$supplier->id]);
        }
        catch (\Exception $e) {
        	 // Hubo error guardando
                    $errorCode = $e->getCode();
                    if ($errorCode == 23000)
                        // Direccion duplicada
                        $response[] = array("error"=>QUERY_EXISTINGADRESS);
                    else
                        if ($errorCode == 2002 || $errorCode == 1044 || $errorCode== 1049)
                            // Si es 2002, es porque no se pudo conectar. No tiro rollback() porque lanza otra vez excepcion porque no esta conectado
                            // Si es 1044, usuario incorrecto
                            // Si es 1049, no existe la tabla
                            $response[] = array("error"=>QUERY_CONN);
                        else
                            // Por si hay algún otro error
                            $response[] = array("error"=>QUERY_UNEXPECTED);
                    
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
            $supplier = Supplier::where('id', $id)->get()->first();
             
             if (!is_null($supplier))
                return $supplier;
            else
                // Si el cliente es nulo, es porque no existe.
                return response()->json(array("error" =>  QUERY_NOTEXISTINGSUPPLIER));
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
        return "";
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
            'razon' => 'required|max:50',
            'telephone' => 'required|max:15|numeric',
            'adress' => 'required|max:30|alpha_num_spaces',
            'email' => 'email|max:30',
            'responsible' => 'required|max:20|alpha_spaces',
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('razon'))
                $response[] = array(        "error" =>  VALIDATOR_DESCRIPTION);
            if ($messages->has('telephone'))
                $response[] = array(        "error" =>  VALIDATOR_TEL);
            if ($messages->has('adress'))
                $response[] = array(        "error" =>  VALIDATOR_ADRESS);
            if ($messages->has('email'))
                $response[] = array(        "error" =>  VALIDATOR_EMAIL);
            if ($messages->has('responsible'))
                $response[] = array(        "error" =>  VALIDATOR_RESPONSIBLE);
            return response()->json($response);
        }

        try{
            $supplier = Supplier::where('id', $id)->get()->first();
        }
        catch (\Exception $e) {
            return response()->json($e);
        }

        $supplier->description = $request->razon;
        $supplier->tel = $request->telephone;
        $supplier->adress = $request->adress;
        $supplier->email = $request->email;
        $supplier->responsible = $request->responsible;
        
        try{
            $supplier->save();
            return "proveedor modificado";
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
                        return $e;
                        $response[] = array("error"=>"Ha ocurrido un error inesperado. Contacte al administrador.");
                    }
                    return response()->json($response);
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
        $supplier = Supplier::where('id', $id)->get();
        try{
            $supplier->delete();
        }
        catch (\Exception $e) {
            return "error al eliminar proveedor";
        }
    }

     /**
     * Search supplier.
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
            return response()->json(["error"=>VALIDATOR_SEARCH]);   
        
        try {
            $supplier = Supplier::select('suppliers.id', 'tel', 'adress', 'email', 'responsible')
                            ->where('responsible', "LIKE", "%".$param."%")
                            ->orWhere('tel', "LIKE", "%".$param."%")
                            ->orWhere('adress', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $supplier;
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
            return $e;
            return response()->json($response);
        }
    }
}
