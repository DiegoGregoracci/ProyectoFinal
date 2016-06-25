// Add article controller.
app.controller("AddArticleController", ["$scope", "articleFactory", "$routeParams", "$location",
                                         function($scope, articleFactory, $routeParams, $location) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.article = {
            "description": "",
            "price": "",
            "cost": "",
            }
    };

    /*
        Add new article
    */
    $scope.add = function() {
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        articleFactory.addArticle($scope.article).then(function (response) {
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
                $scope.status = "No se ha podido crear el articulo. Consulte al administrador";
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