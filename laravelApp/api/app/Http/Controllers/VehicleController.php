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
            'plate' => 'required|max:20',
        ]);

        if ($validator->fails()) {
            return "datos incompletos";
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
        }
        catch(\Exception $e){
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062)
                return "entrada duplicada";
            else
                return "no se puedo agregar el vehiculo";
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
            $vehicle = Vehicle::where('id', $id)->get();
            return $vehicle;
        }
        catch (\Exception $e) {
            return "error al obtener vehiculo";
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
        return "hola";
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
        $vehicle = Vehicle::where('id', $id)->get();

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
        }
        catch (\Exception $e) {
            return "error al actualizar datos";
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
            $vehicle = Vehicle::select('id', 'lastname', 'name')
                            ->where('name', "LIKE", "%".$param."%")
                            ->orWhere('lastname', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $vehicle;
        }
        catch (\Exception $e) {
            return "error al buscar vehiculo";
        }
    }
}
