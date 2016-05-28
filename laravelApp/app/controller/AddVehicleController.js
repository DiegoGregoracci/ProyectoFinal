// Add vehicle controller.
app.controller("AddVehicleController", ["$scope", "vehicleFactory", "$routeParams", "$location",
                                         function($scope, vehicleFactory, $routeParams, $location) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.vehicle = {
            "id_client": "",
            "brand": "",
            "model": "",
            "plate": "",
            "vin": "",
            "year": "",
            "engine": "",
            }
    };

    /*
        Add new vehicle
    */
    $scope.add = function(addOTNext) {
        $scope.vehicle.id_client = $routeParams.id;
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        vehicleFactory.addVehicle($scope.vehicle).then(function (response) {
                if (response.id) {
                    // If status=200 && ID.
                    if (addOTNext)
                        // If addVehicleNext, redirect to next page.
                        $location.path("orden/nuevo/" + response.id);
                    else {
                        // If !addVehicleNext, show success box and reset form.
                        $scope.initialize();
                        $scope.success = true;
                    }
                }
                else {
                    // Error, show error box.
                    $scope.errors = response;
                    $scope.error = true;
                }
                // Disable loading overlay
                $scope.loading = false;
            }, function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.status = "No se ha podido crear el vehículo. Consulte al administrador";
                $scope.loading = false;
                $scope.error = true;
        });
    }

    // Control var. True when sending data. Displays loading overlay.
    $scope.loading = false;
    // Control var. True if error adding a client. Shows error box
    $scope.error = false;
    // Control var. True after adding a client. Shows success box
    $scope.success = false; 
    // Initialize form object.
    $scope.initialize();
}]);