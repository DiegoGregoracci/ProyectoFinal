// Supplier Factory.
// Handles all Supplier's API requests.
app.factory('supplierFactory', ['$http', 'locationPath', function($http, locationPath) {
    var supplierFactory = {};

    // Get all suppliers
    supplierFactory.getSuppliers = function () {
        return $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL);
    };

    // Get supplier by ID
    supplierFactory.getSupplier = function (id) {
        return $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL + '/' + id);
    };

    // Add new supplier
    supplierFactory.addSupplier = function (suplier) {
        return $http.post(locationPath.BASE_URL + locationPath.SUPPLIER_URL + locationPath.ADD_URL, suplier);
    };

    // Search supplier list
    supplierFactory.searchSuppliers = function(param) {
        return $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL + locationPath.SEARCH_URL + '?param=' + param);
    }

    return supplierFactory;
}]);