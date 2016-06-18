// Crear la aplicacion
var app = angular.module('app', [
    'ngRoute'
]);

// Configuracion de Route Provider para manejar las rutas.
app.config(['$routeProvider', function ($routeProvider) {
    
    /*VISTAS ARTICULOS*/
    $routeProvider.when('/articulos/nuevo', {
        templateUrl: 'views/Article/add_article.html',
        controller: 'AddArticleController'
    });

    $routeProvider.when('/articulos/ver/:id', {
        templateUrl: 'views/Article/article.html',
        controller: 'ShowArticleController'
    });

    $routeProvider.when('/articulos/buscar', {
        templateUrl: 'views/Article/search_article.html',
        controller: 'SearchArticleController'
    });
    /*FIN VISTAS ARTICULOS*/

    /*VISTAS PROVEEDORES*/
    $routeProvider.when('/proveedores/nuevo', {
        templateUrl: 'views/Supplier/add_supplier.html',
        controller: 'AddSupplierController'
    });

    $routeProvider.when('/proveedores/ver/:id', {
        templateUrl: 'views/Supplier/supplier.html',
        controller: 'ShowSupplierController'
    });

    $routeProvider.when('/proveedores/buscar', {
        templateUrl: 'views/Supplier/search_supplier.html',
        controller: 'SearchSupplierController'
    });
    /*FIN VISTAS PROVEEDORES*/

    /* VISTAS CLIENTE */
    $routeProvider.when('/cliente/nuevo', {
        templateUrl: 'views/client/add_client.html',
        controller: 'AddClientController'
    });

    $routeProvider.when('/cliente/ver/:id', {
        templateUrl: 'views/client/client.html',
        controller: 'ShowClientController'
    });

    $routeProvider.when('/cliente/buscar', {
        templateUrl: 'views/client/search_client.html',
        controller: 'SearchClientController'
    });
    /* FIN VISTAS CLIENTE */

    
    $routeProvider.when('/personal/nuevo', {
        templateUrl: 'views/add_staff.html',
        controller: 'MainController'
    });

    
    /* VISTAS VEHICULO */
    $routeProvider.when('/vehiculo/nuevo', {
        templateUrl: 'views/vehicle/add_vehicle_step1.html',
        controller: 'SearchClientController'
    });

    $routeProvider.when('/vehiculo/nuevo/:id', {
        templateUrl: 'views/vehicle/add_vehicle_step2.html',
        controller: 'AddVehicleController'
    });

    $routeProvider.when('/vehiculo/ver/:id', {
        templateUrl: 'views/vehicle/vehicle.html',
        controller: 'ShowVehicleController'
    });

    $routeProvider.when('/vehiculo/buscar', {
        templateUrl: 'views/vehicle/search_vehicle.html',
        controller: 'SearchVehicleController'
    });

    $routeProvider.when('/vehiculo/cambiarprop/:id', {
        templateUrl: 'views/vehicle/change_owner.html',
        controller: 'SearchClientController'
    });
    /* FIN VISTAS VEHICULO */

    /* VISTAS STAFF */

    $routeProvider.when('/staff/nuevo', {
        templateUrl: 'views/staff/add_staff.html',
        controller: 'AddStaffController'
    });

    $routeProvider.when('/staff/ver/:id', {
        templateUrl: 'views/staff/staff.html',
        controller: 'ShowStaffController'
    });

    $routeProvider.when('/staff/buscar', {
        templateUrl: 'views/staff/search_staff.html',
        controller: 'SearchStaffController'
    });
    
    /* FIN VISTAS STAFF*/

    $routeProvider.when('/error', {
        templateUrl: 'views/common/error.html'
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