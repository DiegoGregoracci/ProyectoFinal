// Crear la aplicacion
var app = angular.module('app', [
    'ngRoute'
]);

// Configuracion de Route Provider para manejar las rutas.
app.config(['$routeProvider', function ($routeProvider) {
    
    $routeProvider.when('/proveedores/nuevo', {
        templateUrl: 'views/add_supplier.html',
        controller: 'AddSupplierController'
    });

    $routeProvider.when('/cliente/nuevo', {
        templateUrl: 'views/add_client.html',
        controller: 'AddClientController'
    });

    $routeProvider.when('/cliente/ver/:id', {
        templateUrl: 'views/client.html',
        controller: 'ShowClientController'
    });

    $routeProvider.when('/cliente/buscar', {
        templateUrl: 'views/search_client.html',
        controller: 'SearchClientController'
    });

    $routeProvider.when('/personal/nuevo', {
        templateUrl: 'views/add_staff.html',
        controller: 'MainController'
    });

    $routeProvider.when('/vehiculo/nuevo', {
        templateUrl: 'views/add_vehicle_step1.html',
        controller: 'AddVehicleController'
    });

    $routeProvider.when('/vehiculo/nuevo/:id', {
        templateUrl: 'views/add_vehicle_step2.html',
        controller: 'AddVehicleController'
    });
    $routeProvider.when('/vehiculo/buscar', {
        templateUrl: 'views/search_vehicle.html',
        controller: 'SearchVehicleController'
    });

    $routeProvider.when('/error', {
        templateUrl: 'views/error.html'
    });
    $routeProvider.otherwise({
        redirectTo: '/error',
    });
}]); 

// Fix layout after every route change
app.run(function ($rootScope) {
    $rootScope.$on("$routeChangeStart", function(event, next, current) {
        $.AdminLTE.layout.fix();
    });
});

app.controller("MainController", ["$scope", "$http", function($scope, $http) {
    $scope.show = false;
    $scope.send = function(addVehicleNext) {
        $scope.client.name = "asdasd";
    };
    $scope.reset = function() {
        $scope.client = {
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": ""
        };
    };
    $scope.activar = function() {
        $scope.show = true;
    }
    $scope.reset();
    $scope.loading = false;

    $scope.clientes = [{}];
    for (var i = 9; i >= 0; i--) {
        $scope.clientes[i] =  {
            "id": i,
            "name": Math.random().toString(36).substr(2, 5),
            "lastname": Math.random().toString(36).substr(2, 10),
            "telephone1": "asdasd",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "20202020"
        };
    };

}]);

