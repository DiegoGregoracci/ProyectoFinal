app.controller("ControllerListar", ["$scope", "$http", function($scope, $http) {
    $scope.responseDelete = "";
    $scope.loadingDelete = false;
    // Carga el contenido
    $scope.cargar = function() {
        $scope.error = "";
        $scope.personas = [];
        $scope.cargando = true;
        $scope.result = "Cargando...";
        // Realizo la peticion POST
        $scope.action = "list";
        $http.get('api/controller_persona.php?action=list'      
                ).success(function(data, status, headers, config) {
                    // Si status = 200
                    if (!data.error)
                        $scope.personas = data;
                    else
                        $scope.error = data.error;
                    $scope.cargando = false;
                    $scope.result = "Datos obtenidos";
                }).error(function(data, status) {
                    // Si status no es 200.
                    $scope.error = "Ha ocurrido un error inesperado. Error " + status;
                    $scope.enviando = false;
                });
    };
    $scope.eliminar = function(id) {
        if (confirm('¿Estas seguro que deseas borrar la persona')) {
            $scope.loadingDelete = true;
            // Realizo la peticion POST
            $http.post('api/controller_persona.php'
                    , {'action':'eliminar', 'id': id}          
                    ).success(function(data, status, headers, config) {
                        // Si status = 200
                        if (data.msg != "") // Hay mensaje. No hubo errores
                            $scope.responseDelete = data.msg;
                        else  // No hay mensaje, por lo tanto hay error
                            $scope.responseDelete = data.error;
                        $scope.loadingDelete = false;
                        $scope.cargar();
                    }).error(function(data, status) {
                        // Si status no es 200.
                        $scope.responseDelete = "Ha ocurrido un error inesperado al intentar eliminar. Error " + status;
                        $scope.loadingDelete = false;
                    });
        }
    }
    // Llamo a la funcion para cargar contenido.
    $scope.cargar();
        
}]);
