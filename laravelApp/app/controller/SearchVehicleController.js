app.controller("SearchVehicleController", ["$scope", "vehicleFactory", function($scope, vehicleFactory) {
    $scope.searchVehicles = function () {
        $scope.vehicles = [];
        $scope.loading = true;
        $scope.searched = true;
        $scope.error = false;
        vehicleFactory.searchVehicles($scope.search).then(function (response) {
                if (!response.error)
                    // If status=200 && No error msg.
                    $scope.vehicles = response;
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

    $scope.searched = false;
    $scope.loading = false;
    $scope.error = false;
    $scope.errorResponse = "";
}]);