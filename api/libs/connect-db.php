<?php
	include_once("constants.php");
	// Conectarse
	$conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);
	$conn->autocommit(FALSE);
?>