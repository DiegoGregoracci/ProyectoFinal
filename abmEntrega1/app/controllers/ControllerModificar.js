app.controller("ControllerModificar", ["$scope", "$http", "$routeParams", "locationPath", function($scope, $http, $routeParams, locationPath) {
    $scope.id = $routeParams.id;
    // Variables de control
    $scope.cargando = true;
    $scope.enviando = false;
    $scope.response = "";
    $scope.persona = [{id: 0, nombre: "", sexo: "", equipo:""}];

    // Obtengo la persona
    $http.get(locationPath.API_CONTROLLER + locationPath.CONTROLLER_PERSONA + '?action=getpersona&id=' + $scope.id   
            ).success(function(data, status, headers, config) {
                // Si status = 200
                if (!data.error)
                    $scope.persona = data;
                else 
                    $scope.response = data.error;
                $scope.cargando = false;
            }).error(function(data, status) {
                // Si status no es 200.
                $scope.error = "Ha ocurrido un error inesperado. Error " + status;
                $scope.cargando = false;
            });
    $scope.enviar = function(){
        // Variables de control
        $scope.enviando = true;
        $scope.response = "";
        // Reestablezco input equipo si no lo necesito
        if ($scope.sexo == "F")
            $scope.equipo = "";
        // Realizo la peticion POST
        $http.post(locationPath.API_CONTROLLER + locationPath.CONTROLLER_PERSONA
                , {'action':'modificar', 'id': $scope.persona.id, 'nombre': $scope.persona.nombre, 'sexo': $scope.persona.sexo, 'equipo': $scope.persona.equipo}          
                ).success(function(data, status, headers, config) {
                    // Si status = 200
                    if (data.msg != "") // Hay mensaje. No hubo errores
                        $scope.response = data.msg;
                    else  // No hay mensaje, por lo tanto hay error
                        $scope.response = data.error;
                    $scope.enviando = false;
                }).error(function(data, status) {
                    // Si status no es 200.
                    $scope.response = "Ha ocurrido un error inesperado. Error " + status;
                    $scope.enviando = false;
                });
    };
}]);
