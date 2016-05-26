app.controller("ShowClientController", ["$scope", "clientFactory", function($scope, clientFactory) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.client = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "",
            "user": ""
        };
        $scope.savedClient = {
            "id": "",
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "",
            "user": ""
        };
    };
    $scope.resetForm = function() {
        $scope.client = $scope.savedClient;
    }
    $scope.getClient = function () {
        $scope.initialize();
        $scope.loading = true;
        $scope.error = false;

        clientFactory.getClient(13).then(function (response) {
                if (!response.error)
                    // If status=200 && No error msg.
                    $scope.client = $scope.savedClient = response;
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
    $scope.getClient();
    $scope.loading = false;
    $scope.error = false;
    $scope.errorResponse = "";
}]);