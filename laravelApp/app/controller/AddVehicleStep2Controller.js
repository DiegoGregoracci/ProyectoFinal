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

$scope.add = function(addVehicleNext) {
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        vehicleFactory.addVehicle($scope.vehicle).then(function (response) {
                if (response.id) {
                    // If status=200 && ID.
                    if (addVehicleNext)
                        // If addVehicleNext, redirect to next page.
                        $location.path("vehiculo/nuevo/" + response.id);
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
                $scope.status = "No se ha podido crear el vehiculo. Consulte al administrador";
                $scope.loading = false;
                $scope.error = true;
        });
    }