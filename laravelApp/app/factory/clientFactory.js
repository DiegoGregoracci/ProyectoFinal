// Client Factory.
// Handles all Client's API requests.
app.factory('clientFactory', ['$http', '$q', 'locationPath', function($http, $q, locationPath) {
    var clientFactory = {};

    // Get all clients
    clientFactory.getClients = function () {
        return $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL);
    };

    // Get client by ID
    clientFactory.getClient = function (id) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL + "/" + id)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;    
    };

    // Add new client
    clientFactory.addClient = function (client) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.post(locationPath.BASE_URL + locationPath.CLIENT_URL, client)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Search client list
    clientFactory.searchClients = function(param) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL + locationPath.SEARCH_URL + param)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    }

    return clientFactory;
}]);