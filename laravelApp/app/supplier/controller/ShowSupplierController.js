app.controller("ShowSupplierController", ["$scope", "supplierFactory", "$routeParams", function($scope, supplierFactory, $routeParams) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.supplier = {
            "razon": "",
            "telephone": "",
            "adress": "",
            "email": "",
            "responsible": ""
        };
        $scope.savedSupplier = {
            "razon": "",
            "telephone": "",
            "adress": "",
            "email": "",
            "responsible": ""
        };
        
    };
    $scope.restoreData = function() {
        $scope.supplier = angular.copy($scope.savedSupplier);
    }
    $scope.getSupplier = function () {
        $scope.initialize();
        $scope.loading = true;
        $scope.errorLoading = false;

        supplerFactory.getSupplier($routeParams.id).then(function (response) {
                if (!response.error) {
                    // If status=200 && No error msg.
                    $scope.supplier = response.supplier;
                    $scope.savedSupplier = angular.copy($scope.supplier);
                   
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
    $scope.updateSupplier = function () {
        $scope.updating = true;
        $scope.errorUpdate = false;
        $scope.successUpdate = false;
        $scope.errors = [];

        supplierFactory.updateSupplier($routeParams.id, $scope.supplier).then(function (response) {
                if (response.id) {
                    // If status=200 && No error msg.
                    $scope.savedSupplier = angular.copy($scope.supplier);
                    $scope.successUpdate = true;
                }
                else {
                    // Error, show error box.
                    $scope.errors = response;
                    $scope.errorUpdate = true;
                }
                // Disable loading overlay
                $scope.updating = false;
            }, function (error) {
                // HTTP Error. Force status msg, show error box, disable loading overlay.
                $scope.errorResponse = "Error inesperado. Consulte al administrador";
                $scope.updating = false;
                $scope.error = true;
        });
    };
    $scope.updating = false;
    $scope.error = false;
    $scope.errorResponse = "";
    $scope.getSupplier();
}]);