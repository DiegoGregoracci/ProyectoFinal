// Add vehicle controller.
app.controller("AddVehicleStep2Controller", ["$scope", "vehicleFactory", "$location", "$filter", function($scope, vehicleFactory, $location, $filter) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.vehicle = {
            "brand": "",
            "model": "",
            "plate": "",
            "vin": "",
            "year": "",
            "engine": "",
            }
    };

    $scope.reset = function() {
        $scope.initialize();
    };

    /*
        Add new vehicle
    */
    $scope.add = function(addVehicleNext) {
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        vehicleFactory.addVehicle($scope.vehicle).then(function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.status = "No se ha podido crear el vehiculo. Consulte al administrador";
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