// Client Factory.
// Handles all Client's API requests.
app.factory('articleFactory', ['$http', '$q', 'locationPath', function($http, $q, locationPath) {
    var articleFactory = {};

    // Get article by ID
    articleFactory.getArticle = function (id) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.ARTICLE_URL + "/" + id)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;    
    };

    // Add new article
    articleFactory.addArticle = function (article) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.post(locationPath.BASE_URL + locationPath.ARTICLE_URL, article)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Search article list
    articleFactory.searchArticles = function(param) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.ARTICLE_URL + locationPath.SEARCH_URL + param)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Update article
    articleFactory.updateArticle = function (id, article) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.put(locationPath.BASE_URL + locationPath.ARTICLE_URL + "/" + id, article)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };


    return articleFactory;
}]);