<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Vehicle;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Database\DatabaseManager;
// Include validators.php to extend Validators
require app_path().'/validators.php';

class Vehiclecontroller extends Controller
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
            'plate' => 'required|alpha_num|max:20|min:6',
            'brand' => 'required|alpha_spaces|max:20|min:3',
            'model' => 'required|alpha_spaces|max:20|min:3'
        ]);

        if ($validator->fails()) {
            return "datos incompletos";
        }
        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('plate'))
                $response[] = array("error"=>"La patente debe tener entre 6 y 8 caractéres.");
            if ($messages->has('brand'))
                $response[] = array("error"=>"La marca debe tener entre 3 y 20 caractéres alfanuméricos.");
            if ($messages->has('model'))
                $response[] = array("error"=>"El modelo debe tener entre 3 y 20 caractéres alfanuméricos.");
            return response()->json($response);
        }


        $vehicle = new Vehicle();
        $vehicle->id_client = $newuser->id;
        $vehicle->brand = $request->brand;
        $vehicle->model = $request->model;
        $vehicle->plate = $request->plate;
        $vehicle->vin = $request->vin;
        $vehicle->year = $request->year;
        $vehicle->engine = $request->engine;
        
        try{
            $vehicle->save();
            return response()->json(["id"=>$vehicle->id]);            
        }
        catch(\Exception $e){
            $errorCode = $e->getcode();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    //el id es el de un vehiculo
        try{
            $vehicle = Vehicle::where('id', $id)
                            ->get();

            $response = array(
                                "vehicles"  =>  $vehicles
                             );
            return response()->json($response);
        }
        catch (\Exception $e) {
            return $e;
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
            'plate' => 'required|alpha_num|max:20|min:6',
            'brand' => 'required|alpha_spaces|max:20|min:3',
            'model' => 'required|alpha_spaces|max:20|min:3'
        ]);

        if ($validator->fails()) {
            return "datos incompletos";
        }
        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('plate'))
                $response[] = array("error"=>"La patente debe tener entre 6 y 8 caractéres.");
            if ($messages->has('brand'))
                $response[] = array("error"=>"La marca debe tener entre 3 y 20 caractéres alfanuméricos.");
            if ($messages->has('model'))
                $response[] = array("error"=>"El modelo debe tener entre 3 y 20 caractéres alfanuméricos.");
            return response()->json($response);
        }

        try{
            $vehicle = Vehicle::where('id', $id)->get();
        }
        catch (\Exception $e) {
            return response()->json($e);
        }
        

        $vehicle = new Vehicle();
        $vehicle->brand = $request->brand;
        $vehicle->model = $request->model;
        $vehicle->plate = $request->plate;
        $vehicle->vin = $request->vin;
        $vehicle->year = $request->year;
        $vehicle->engine = $request->engine;
        
        try{
            $vehicle->save();
            return response()->json(["id"=>$vehicle->id]);
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
        $vehicle = Vehicle::where('id', $id)->get();
        try{
            $vehicle->delete();
        }
        catch (\Exception $e) {
            return "error al eliminar vehiculo";
        }
    }

    /**
     * Search vehicles.
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
            $vehicle = Vehicle::select('id', 'brand', 'model', 'plate')
                            ->where('plate', "LIKE", "%".$param."%")
                            ->orWhere('model', "LIKE", "%".$param."%")
                             ->orWhere('brand', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $vehicle;
        }
        catch (\Exception $e) {
            return "error al buscar vehiculo";
        }
    }
}
