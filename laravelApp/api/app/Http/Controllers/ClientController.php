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
        ]);

        if ($validator->fails()) {
            return "datos incompletos";
        }
        // Inicio transacción
        DB::beginTransaction();

        // Crear nuevo usuario
        $user = new User;
        $user->user = $request->user;
        $user->password = "test";

        try {
            $user->save();
            DB::commit();
            return "agregado";
        }
        catch (\Exception $e) {
            DB::rollback();
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062)
                return "entrada duplicada";
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
