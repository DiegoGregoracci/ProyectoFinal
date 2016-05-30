// Add client controller.
app.controller("AddSupplierController", ["$scope", "supplierFactory", "$location", function($scope, supplierFactory, $location) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.supplier = {
            "description": "",
            "tel": "",
            "address": "",
            "email": "",
            "responsible": ""
        };
    };

    $scope.add = function () {
    /*
        Add new provider
    */
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
