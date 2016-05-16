// Add client controller.
app.controller("AddClientController", ["$scope", "clientFactory", "$location", function($scope, clientFactory, $location) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.client = {
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": ""
        };
    };

    /*
        Add new client
        @param addVehicleNext: 1: redirect to new vehicle when done, 0: redirect to client info
    */
    $scope.add = function(addVehicleNext) {
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        clientFactory.addClient($scope.client).then(function (response) {
                if (response.msg != "") {
                    // If status=200 && No error msg.
                    if (addVehicleNext)
                        // If addVehicleNext, redirect to next page.
                        $location.path("vehiculo/agregar/" + response.msg);
                    else {
                        // If !addVehicleNext, show success box and reset form.
                        $scope.initialize();
                        $scope.success = true;
                    }
                }
                else {
                    // Error, show error box.
                    $scope.error = true;
                    $scope.status = response.error;
                }
                // Disable loading overlay
                $scope.loading = false;
            }, function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.status = "No se ha podido crear el cliente. Consulte al administrador";
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
