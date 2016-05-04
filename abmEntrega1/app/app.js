// Crear la aplicacion
var app = angular.module('app', [
    'ngRoute'
]);

app.constant("locationPath", {
    "API_CONTROLLER": "api/controller/",
    "CONTROLLER_PERSONA": "controller_persona.php",
    "CONTROLLER_CLIENTE": "ClienteController.php",
    "CONTROLLER_VEHICULO": "VehiculoController.php"
});

// Configuracion de Route Provider para manejar las rutas.
app.config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: 'views/index.html',
        controller: 'ControllerListar'
    });
    $routeProvider.when('/agregar', {
        templateUrl: 'views/agregar.html',
        controller: 'ControllerAgregar'
    });
    $routeProvider.when('/modificar/:id', {
        templateUrl: 'views/modificar.html',
        controller: 'ControllerModificar'
    });
    $routeProvider.when('/error', {
        templateUrl: 'views/error.html'
    });
    $routeProvider.otherwise({
        redirectTo: '/error',
    });
}]); 