<?php

/**
	ERRORES DE VALIDACION
*/
	// COMUNES
	define("VALIDATOR_SEARCH", "El parámetro de búsqueda solo puede contener letras, números y espacios.");
	define("VALIDATOR_BADREQUEST", "Solicitud incorrecta");

	// CLIENTES
	define("VALIDATOR_USER", "El nombre de usuario debe tener entre 3 y 20 caractéres alfanuméricos sin espacios.");
	define("VALIDATOR_NAME", "El nombre debe tener entre 3 y 20 caractéres alfanuméricos.");
	define("VALIDATOR_NAME_ALPHA", "El nombre debe tener entre 3 y 20 letras y espacios.");
	define("VALIDATOR_LASTNAME", "El apellido debe tener entre 3 y 20 caractéres alfanuméricos.");
	define("VALIDATOR_LASTNAME_ALPHA", "El apellido debe tener entre 3 y 20 letras y espacios.");
	define("VALIDATOR_ADDRESS", "La dirección puede tener un máximo de 20 caractéres alfanuméricos.");
	define("VALIDATOR_TELEPHONE1", "El teléfono 1 puede tener un máximo de 15 caractéres alfanuméricos.");
	define("VALIDATOR_TELEPHONE2", "El teléfono 1 puede tener un máximo de 15 caractéres alfanuméricos.");
	define("VALIDATOR_EMAIL", "El correo electrónico debe tener formato de e-mail. y hasta 30 caractéres.");

	// VEHICULOS
	define("VALIDATOR_IDUSER", "No se ha definido el número de cliente de manera correcta.");
	define("VALIDATOR_BRAND", "La marca debe tener entre 2 y 15 caractéres alfanuméricos.");
	define("VALIDATOR_MODEL", "El modelo debe tener entre 2 y 15 caractéres alfanuméricos.");
	define("VALIDATOR_PLATE", "La patente debe tener entre 6 y 8 caractéres alfanuméricos sin espacios.");
	define("VALIDATOR_YEAR", "El año sólo puede contener números.");
	define("VALIDATOR_ENGINE", "El motor puede contener hasta 15 caractéres.");
	define("VALIDATOR_VIN", "El VIN debe tener un máximo de 17 caractéres alfanuméricos sin espacios.");

	// PROVEEDOR

	define("VALIDATOR_DESCRIPTION", "La descripcion puede tener un maximo de 50 caractéres.");
	define("VALIDATOR_TEL", "El teléfono puede tener un máximo de 15 caractéres alfanuméricos.");
	define("VALIDATOR_ADRESS", "La dirección puede tener un máximo de 30 caractéres alfanuméricos.");
	define("VALIDATOR_EMAIL", "El email puede tener un máximo de 30 caractéres alfanuméricos.");
	define("VALIDATOR_RESPONSIBLE", "El responsable puede tener un máximo de 20 caractéres alfanuméricos.");

	// ARTICULOS

	define("VALIDATOR_PRICE", "El teléfono puede tener un máximo de 15 caractéres alfanuméricos.");
	define("VALIDATOR_COST", "El teléfono puede tener un máximo de 15 caractéres alfanuméricos.");

/**
	ERRORES DE TRANSACCIÓN
*/
	
	// COMUNES
	define("QUERY_CONN", "Error de conexión a la base de datos.");
	define("QUERY_UNEXPECTED", "Ha ocurrido un error inesperado. Contacte al administrador.");

	// CLIENTES
	define("QUERY_EXISTINGUSER", "El nombre de usuario elegido ya existe en la base de datos.");
	define("QUERY_NOTEXISTINGUSER", "El cliente no existe.");

	// VEHICULOS
	define("QUERY_EXISTINGPLATE", "La patente ya existe en la base de datos.");
	define("QUERY_NOTEXISTINGVEHICLE", "Vehículo inexistente");

	// STAFF
	define("QUERY_NOTEXISTINGSTAFF", "Personal inexistente");

