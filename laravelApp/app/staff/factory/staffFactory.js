// Staff Factory.
// Handles all Staff's API requests.
app.factory('staffFactory', ['$http', '$q', 'locationPath', function($http, $q, locationPath) {
    var staffFactory = {};

    // Get staff by ID
    staffFactory.getStaff = function (id) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.STAFF_URL + "/" + id)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;    
    };

    // Add new staff
    staffFactory.addStaff = function (staff) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.post(locationPath.BASE_URL + locationPath.STAFF_URL, staff)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Search staff list
    staffFactory.searchStaff = function(param) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.STAFF_URL + locationPath.SEARCH_URL + param)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Update staff
    staffFactory.updateStaff = function (id, staff) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.put(locationPath.BASE_URL + locationPath.STAFF_URL + "/" + id, staff)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };


    return staffFactory;
}]);