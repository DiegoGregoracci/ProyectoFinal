// Add vehicle controller.
app.controller("AddVehicleController", ["$scope", "vehicleFactory", "$location", "$filter", function($scope, vehicleFactory, $location, $filter) {
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

    /*
        Add new vehicle
    */
    $scope.add = function () {
    
        $scope.success=true;
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
