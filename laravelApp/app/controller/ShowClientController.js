app.controller("ShowClientController", ["$scope", "clientFactory", "$routeParams", function($scope, clientFactory, $routeParams) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.client = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "",
            "user": ""
        };
        $scope.savedClient = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "",
            "user": ""
        };
        $scope.vehicles = [];
    };
    $scope.restoreData = function() {
        $scope.client = angular.copy($scope.savedClient);
    }
    $scope.getClient = function () {
        $scope.initialize();
        $scope.loading = true;
        $scope.error = false;

        clientFactory.getClient($routeParams.id).then(function (response) {
                if (!response.error) {
                    // If status=200 && No error msg.
                    $scope.client = response.client;
                    $scope.savedClient = angular.copy($scope.client);
                    $scope.vehicles = response.vehicles;
                }
                else {
                    // Error, show error box.
                    $scope.error = true;
                    $scope.errorResponse = response.error;
                }
                // Disable loading overlay
                $scope.loading = false;
            }, function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.errorResponse = "Error inesperado. Consulte al administrador";
                $scope.loading = false;
                $scope.error = true;
        });
    };
    $scope.loading = false;
    $scope.error = false;
    $scope.errorResponse = "";
    $scope.getClient();
}]);