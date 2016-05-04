<?php
// Desactivo errores para manejarlos por JSON.
error_reporting(0);

// Constantes de DB
define("DB_HOST","localhost");
define("DB_NAME","personas");
define("DB_USER","root");
define("DB_PWD","");

// Rutas
define("CONTROLLER_PATH", "./api/controller/");
define("MODEL_PATH", "./api/model/");
define("LIB_PATH", "./api/lib/");
?>