app.controller("ShowArticleController", ["$scope", "articleFactory", "$routeParams", function($scope, articleFactory, $routeParams) {
    /*
        Initialize object form.
    */
    $scope.initialize = function () {
        $scope.article = {
            "id": "",
            "description": "",
            "price": "",
            "cost": "",
        };
        $scope.savedArticle = {
            "id": "",
            "description": "",
            "price": "",
            "cost": "",
        };
        $scope.articles = [];
    };
    $scope.restoreData = function() {
        $scope.article = angular.copy($scope.savedArticle);
    }
    $scope.getArticle = function () {
        $scope.initialize();
        $scope.loading = true;
        $scope.errorLoading = false;

        articleFactory.getArticle($routeParams.id).then(function (response) {
                if (!response.error) {
                    // If status=200 && No error msg.
                    $scope.article = response.article;
                    $scope.savedArticle = angular.copy($scope.article);
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
    $scope.updateArticle = function () {
        $scope.updating = true;
        $scope.errorUpdate = false;
        $scope.successUpdate = false;
        $scope.errors = [];

        articleFactory.updateArticle($routeParams.id, $scope.article).then(function (response) {
                if (response.id) {
                    // If status=200 && No error msg.
                    $scope.savedArticle = angular.copy($scope.article);
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
    $scope.getArticle();
}]);