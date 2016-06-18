app.controller("ChangeOwnerController", ["$scope", "clientFactory", "vehicleFactory", function($scope, clientFactory, vehicleFactory) {
    $scope.searchClient = function () {
        $scope.clients = [];
        $scope.loading = true;
        $scope.searched = true;
        $scope.error = false;
        clientFactory.searchClients($scope.search).then(function (response) {
                if (!response.error)
                    // If status=200 && No error msg.
                    $scope.clients = response;
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
    $scope.changeOwner = function (client_id) {
        
    }
    $scope.searched = false;
    $scope.loading = false;
    $scope.error = false;
    $scope.errorResponse = "";
}]);