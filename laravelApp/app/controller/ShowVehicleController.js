app.controller("ShowVehicleController", ["$scope", "vehicleFactory", "$routeParams", function($scope, vehicleFactory, $routeParams) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.vehicle = {
            "id": "",
            "id_client": "",
            "brand": "",
            "model": "",
            "plate": "",
            "vin": "",
            "year": "",
            "engine": ""
        };
        $scope.savedVehicle = {
            "id": "",
            "id_client": "",
            "brand": "",
            "model": "",
            "plate": "",
            "vin": "",
            "year": "",
            "engine": ""
        };
    };
    $scope.restoreData = function() {
        $scope.vehicle = angular.copy($scope.savedVehicle);
    }
    $scope.getVehicle = function () {
        $scope.initialize();
        $scope.loading = true;

        vehicleFactory.getVehicle($routeParams.id).then(function (response) {
                if (!response.error) {
                    // If status=200 && No error msg.
                    $scope.vehicle = response;
                    $scope.savedVehicle = angular.copy($scope.vehicle);
                   
                }
                else {
                    // Error, show error box.
                    $scope.errorLoading = true;
                    $scope.errorResponse = response.error;
                }
                // Disable loading overlay
                $scope.loading = false;
            }, function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.errorResponse = "Error inesperado. Consulte al administrador";
                $scope.loading = false;
                $scope.errorLoading = true;
        });
    };
    $scope.updateVehicle = function () {
        $scope.loading = true;
        $scope.errorUpdate = false;
        $scope.successUpdate = false;
        $scope.errors = [];

        vehicleFactory.updateVehicle($routeParams.id, $scope.client).then(function (response) {
                if (response.id) {
                    // If status=200 && No error msg.
                    $scope.savedVehicle = angular.copy($scope.vehicle);
                    $scope.successUpdate = true;
                }
                else {
                    // Error, show error box.
                    $scope.errors = response;
                    $scope.errorUpdate = true;
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
    $scope.errorLoading = false;
    $scope.errorResponse = "";
    $scope.getVehicle();
}]);