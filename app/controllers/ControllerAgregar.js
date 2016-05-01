app.controller("ControllerAgregar", ["$scope", "$http", "locationPath", function($scope, $http, locationPath) {
    // Variable para desactivar el boton cuando se está enviando
    $scope.enviando = false;    
    // Funcion POST
    $scope.enviar = function(){
        // Variables de control
        $scope.enviando = true;
        $scope.response = "Cargando...";
        // Reestablezco input equipo si no lo necesito
        if ($scope.sexo == "F")
            $scope.equipo = "";
        // Realizo la peticion POST
        $http.post(locationPath.API_CONTROLLER + locationPath.CONTROLLER_PERSONA
                , {'action':'agregar', 'nombre': $scope.nombre, 'sexo': $scope.sexo, 'equipo': $scope.equipo}          
                ).success(function(data, status, headers, config) {
                    // Si status = 200
                    if (data.msg != '') {
                        // Hay mensaje. No hubo errores
                        $scope.response = data.msg;
                        // Reseteo formulario
                        $scope.nombre = "";
                        $scope.sexo = "";
                        $scope.equipo = "";
                        $scope.formulario.$setPristine();
                    }
                    else
                        // No hay mensaje, por lo tanto hay error
                        $scope.response = data.error;
                    $scope.enviando = false;
                }).error(function(data, status) {
                    // Si status no es 200.
                    $scope.response = "Ha ocurrido un error inesperado. Error " + status;
                    $scope.enviando = false;
                });
    };
}]);
