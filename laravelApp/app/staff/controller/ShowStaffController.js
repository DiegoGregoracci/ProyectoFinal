app.controller("ShowStaffController", ["$scope", "staffFactory", "$routeParams", function($scope, staffFactory, $routeParams) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.staff = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone": "",
            "address": "",
            "email": ""
        };
        $scope.savedStaff = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone": "",
            "address": "",
            "email": ""
        };
        $scope.vehicles = [];
    };
    $scope.restoreData = function() {
        $scope.staff = angular.copy($scope.savedstaff);
    }
    $scope.getStaff = function () {
        $scope.initialize();
        $scope.loading = true;
        $scope.errorLoading = false;

        staffFactory.getStaff($routeParams.id).then(function (response) {
                if (!response.error) {
                    // If status=200 && No error msg.
                    $scope.staff = response.staff;
                    $scope.savedStaff = angular.copy($scope.staff);
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
    $scope.updateStaff = function () {
        $scope.updating = true;
        $scope.errorUpdate = false;
        $scope.successUpdate = false;
        $scope.errors = [];

        staffFactory.updateStaff($routeParams.id, $scope.staff).then(function (response) {
                if (response.id) {
                    // If status=200 && No error msg.
                    $scope.savedStaff = angular.copy($scope.staff);
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
    $scope.getStaff();
}]);