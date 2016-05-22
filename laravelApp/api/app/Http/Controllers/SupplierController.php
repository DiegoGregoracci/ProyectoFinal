<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Supplier;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Database\DatabaseManager;
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
            'email' => 'required|max:20',
        ]);

        if ($validator->fails()) {
            return "datos incompletos";
        }

        $supplier = new Supplier();
        $supplier->description = $request->description;
        $supplier->tel = $request->tel;
        $supplier->adress = $request->adress;
        $supplier->email = $request->email;
        $supplier->responsible = $request->responsible;

        try{
        	$supplier->save();
        	return "proveedor agregado";
        }
        catch (\Exception $e) {
        	$errorCode = $e->errorInfo[1];
            if ($errorCode == 1062)
                return "entrada duplicada";
            else
            	return "error al guardar proveedor";
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
            $supplier = Supplier::where('id', $id)->get();
            return $supplier;
        }
        catch (\Exception $e) {
            return "error al obtener proveedor";
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
        $supplier = Supplier::where('id', $id)->get();

        $supplier = new Supplier();
        $supplier->description = $request->description;
        $supplier->tel = $request->tel;
        $supplier->adress = $request->adress;
        $supplier->email = $request->email;
        $supplier->responsible = $request->responsible;
        
        try{
            $supplier->save();
            return "proveedor modificado";
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
        $supplier = Supplier::where('id', $id)->get();
        try{
            $supplier->delete();
            return "proveedor eliminado";
        }
        catch (\Exception $e) {
            return "error al eliminar proveedor";
        }
    }
}
