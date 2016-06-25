// Add client controller.


app.controller("AddSupplierController", ["$scope", "supplierFactory","$routeParams", "$location", 
                                    function($scope, supplierFactory, $routeParams, $location) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.supplier = {
            "razon": "",
            "telephone": "",
            "adress": "",
            "email": "",
            "responsible": ""
        };
    };

    $scope.add = function() {
        $scope.supplier.id = $routeParams.id;
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        supplierFactory.addSupplier($scope.supplier).then(function (response) {
                if (response.id) {
                    // If status=200 && ID.
                    $scope.initialize();
                    $scope.success = true;
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
                $scope.status = "No se ha podido dar de alta el proveedor. Consulte al administrador";
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
