<?php
sleep(1);
/*
    Este controller recibe peticiones GET y POST.
    Los GET dependen del action que recibe: 
        Si action = list, devuelve la lista completa.
        Si action = getpersona, obtiene el id por GET y devuelve datos de esa persona.
    Los POST dependen del action que recibe:
        Si action = agregar, crea nueva persona.
        Si action = modificar, modifica persona existente.
        Si action = eliminar, elimina persona.
*/

// Defino variables
define("DB_HOST","localhost");
define("DB_NAME","personas");
define("DB_USER","root");
define("DB_PWD","");
define("ERR_DB", "No se pudo conectar a la base de datos.");
define("ERR_EMPTYDB", "No se han encontrado personas en la base de datos.");
define("ERR_NORESULT", "El ID solicitado no existe en la base de datos.");
define("ERR_BADREQUEST", "Petición incorrecta.");
define("ERR", "Ocurrió un error inesperado");

// Desactivo errores para poder manejarlos con JSON.
error_reporting(0);

// Si es una peticion GET
if (isset($_GET['action']) && $_GET['action']!="") {
        //Conexion a DB
        $conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
        // Verifico conexion
        if ($conn->connect_error)
            $response = array("error" => ERR_DB);
        else {
            // Conexion correcta.
            $action = $_GET["action"];
            $conn->autocommit(FALSE);
            switch ($action) {
                case 'list':
                    // Solicito personas
                    $sql = "SELECT * FROM persona ORDER BY id DESC";
                    $rs = $conn->query($sql);
                    $numrows = $rs->num_rows;
                    if ($numrows > 0) 
                        // Si hay personas
                        while($row = $rs->fetch_assoc())
                            // Mientras haya filas las agrego al array
                            $response[] = array("id"=>$row["id"], "nombre"=>$row["nombre"], "sexo"=>$row["sexo"], "equipo"=>$row["equipo"]);   
                    else 
                        // No hay personas, devuelvo array vacio.
                        $response = array("error" => ERR_EMPTYDB);
                    break;
                case 'getpersona':
                    if (isset($_GET["id"]) && $_GET["id"]!="") {
                        // Si esta indicado el ID de la persona a obtener
                        $id = $_GET["id"];
                        $sql = "SELECT * FROM persona WHERE id=".$id."";
                        $rs = $conn->query($sql);
                        if ($rs->num_rows > 0) {
                            // Si existe el ID, cargo el response.
                            $row = $rs->fetch_assoc();
                            $response = array("id"=>$row["id"], "nombre"=>$row["nombre"], "sexo"=>$row["sexo"], "equipo"=>$row["equipo"]);   
                        }
                        else
                            // No existe el ID.
                            $response = array("msg" => "", "error"=>ERR_NORESULT);
                    }
                    else
                        // Falta el ID.
                        $response = array("error"=>ERR_BADREQUEST);
                    break;
                }
        }
        // Retorno resultado encriptado
        $json = json_encode($response);
        print_r($json);
}
// Si no hay GET, veo si hay entrada JSON.
else if (file_get_contents("php://input")) { 
    // Decodifico los datos en JSON
    $data = json_decode(file_get_contents("php://input"));
    $action = $data->action;
    //Conexion a DB
    $conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
    // Verifico conexion
    if ($conn->connect_error)
        $response = array("msg" => "", "error" => ERR_DB);
    else {
        // Conexion correcta.
        $conn->autocommit(FALSE);
        switch ($action) {
            case 'agregar':
                // Solicitud para insertar nueva persona
                $nombre = $data->nombre;
                $sexo = $data->sexo;
                // Valido si puede tener equipo
                if ($sexo == "M" && isset($data->equipo))
                    $equipo = $data->equipo;
                else
                    $equipo = "";   
                // Inserto nueva persona
                $sql = "INSERT INTO persona (nombre, sexo, equipo) values (?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nombre, $sexo, $equipo);
                $stmt->execute();
                $conn->commit();
                // Verifico inserción correcta
                if ($stmt->insert_id)
                    $response = array("msg" => "Se agrego a la base de datos nombre: ".$nombre.", sexo: ".$sexo.", y equipo: ".$equipo."", "error" => "");
                else
                    $response = array("msg" => "", "error" => ERR);
                break;
            case 'modificar':
                // Solicitud para insertar nueva persona
                $id = $data->id;
                $nombre = $data->nombre;
                $sexo = $data->sexo;
                // Valido si puede tener equipo
                if ($sexo == "M" && isset($data->equipo))
                    $equipo = $data->equipo;
                else
                    $equipo = "";
                // Modifico la DB
                $sql = "UPDATE persona set nombre=(?), sexo=(?), equipo=(?) WHERE id=".$id."";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nombre, $sexo, $equipo);
                $stmt->execute();
                $conn->commit();
                $response = array("msg" => "Se modifico la base de datos nombre: ".$nombre.", sexo: ".$sexo.", y equipo: ".$equipo."", "error" => "");
                break;
            case 'eliminar':
                $id = $data->id;
                if ($id) {
                    //Conexion correcta. Inserto a DB
                    $sql = "DELETE FROM persona WHERE id=$id";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $conn->commit();
                    $response = array("msg" => "Persona ID:".$id." eliminada de la base de datos.", "error" => "");       
                }
                else
                    $response = array("msg"=>"", "error"=>ERR_BADREQUEST);
                break;
        }
    }
    // Retorno resultado encriptado
    $json = json_encode($response);
    print_r($json);
}
?>