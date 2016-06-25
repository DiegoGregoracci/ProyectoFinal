// Supplier Factory.
// Handles all Supplier's API requests.
app.factory('supplierFactory', ['$http', '$q', 'locationPath', function($http, $q, locationPath) {
    var supplierFactory = {};

    // Get all suppliers
    supplierFactory.getSuppliers = function () {
        var defered = $q.defer();  
        var promise = defered.promise;
       $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;    

    };

    // Get supplier by ID
    supplierFactory.getSupplier = function (id) {
        var defered = $q.defer();  
        var promise = defered.promise;
       $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL + '/' + id)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;   
        
    };

    // Add new supplier
    supplierFactory.addSupplier = function (suplier) {
         var defered = $q.defer();  
        var promise = defered.promise;
        $http.post(locationPath.BASE_URL + locationPath.SUPPLIER_URL, suplier)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;

    };

    // Search supplier list
    supplierFactory.searchSuppliers = function(param) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.SUPPLIER_URL + locationPath.SEARCH_URL + '?param=' + param)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;

    }

    return supplierFactory;
}]);