// Client Factory.
// Handles all Client's API requests.
app.factory('clientFactory', ['$http', 'locationPath', function($http, locationPath) {
    var clientFactory = {};

    // Get all clients
    supplierFactory.getSuppliers = function () {
        return $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL);
    };

    // Get client by ID
    supplierFactory.getSupplier = function (id) {
        return $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL + '/' + id);
    };

    // Add new client
    supplierFactory.addSupplier = function (client) {
        return $http.post(locationPath.BASE_URL + locationPath.CLIENT_URL + locationPath.ADD_URL, client);
    };

    // Search client list
    supplierFactory.searchSuppliers = function(param) {
        return $http.get(locationPath.BASE_URL + locationPath.CLIENT_URL + locationPath.SEARCH_URL + '?param=' + param);
    }

    return supplierFactory;
}]);