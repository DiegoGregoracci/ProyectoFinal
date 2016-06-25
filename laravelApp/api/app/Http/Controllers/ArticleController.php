<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Article;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Database\DatabaseManager;
// Include validators.php to extend Validators
require app_path().'/Validators.php';
// Include constantes
require app_path().'/Constants.php';

class ArticleController extends Controller
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
            'description' => 'required|max:50',
            'price' => 'numeric',
            'cost' => 'numeric',
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('description'))
                $response[] = array(        "error" =>  VALIDATOR_DESCRIPTION);
            if ($messages->has('price'))
                $response[] = array(        "error" =>  VALIDATOR_PRICE);
            if ($messages->has('cost'))
                $response[] = array(        "error" =>  VALIDATOR_COST);
            return response()->json($response);
        }

        $article = new Article();
        $article->description = $request->description;
        $article->price = $request->price;
        $article->cost = $request->cost;

        try{
        	$article->save();
        	return response()->json(["id"=>$article->id]);
        }
        catch (\Exception $e) {
        	 // Hubo error guardando
                    $errorCode = $e->getCode();
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
            $article = Article::where('id', $id)->get()->first();
             
             if (!is_null($article))
                return $article;
            else
                // Si el cliente es nulo, es porque no existe.
                return response()->json(array("error" =>  QUERY_NOTEXISTINGARTICLE));
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
            'description' => 'required|max:50',
            'price' => 'numeric',
            'cost' => 'numeric',
        ]);

        // Compruebo mensajes. Con $messages->has('field') sabes si el validator fallo para ese field
        if ($validator->fails()) { 
            $messages = $validator->messages();
            if ($messages->has('description'))
                $response[] = array(        "error" =>  VALIDATOR_DESCRIPTION);
            if ($messages->has('price'))
                $response[] = array(        "error" =>  VALIDATOR_PRICE);
            if ($messages->has('cost'))
                $response[] = array(        "error" =>  VALIDATOR_COST);
            return response()->json($response);
        }

        try{
            $articles = Article::where('id', $id)->get()->first();
        }
        catch (\Exception $e) {
            return response()->json($e);
        }

        $articles->description = $request->description;
        $articles->price = $request->price;
        $articles->cost = $request->cost;
        
        try{
            $articles->save();
            return "articulo modificado";
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
        $article = Article::where('id', $id)->get();
        try{
            $article->delete();
        }
        catch (\Exception $e) {
            return "error al eliminar articulo";
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
            $article = Article::select('articles.id', 'description', 'price', 'cost')
                            ->where('cost', "LIKE", "%".$param."%")
                            ->orWhere('price', "LIKE", "%".$param."%")
                            ->orWhere('description', "LIKE", "%".$param."%")
                            ->orWhere('id', "=", $param)
                            ->get();
            return $article;
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
