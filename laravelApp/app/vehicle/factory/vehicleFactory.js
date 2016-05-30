// Vehicle Factory.
// Handles all Client's API requests.
app.factory('vehicleFactory', ['$http', '$q', 'locationPath', function($http, $q, locationPath) {
    var vehicleFactory = {};

    // Get all vehicles
    vehicleFactory.getVehicles = function () {
        return $http.get(locationPath.BASE_URL + locationPath.VEHICLE_URL);
    };

    // Get vehicle by ID
    vehicleFactory.getVehicle = function (id) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.VEHICLE_URL + "/" + id)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;    
    };

    // Add new vehicle
    vehicleFactory.addVehicle = function (vehicle) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.post(locationPath.BASE_URL + locationPath.VEHICLE_URL, vehicle)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    };

    // Search vehicle list
    vehicleFactory.searchVehicles = function(param) {
        var defered = $q.defer();  
        var promise = defered.promise;
        $http.get(locationPath.BASE_URL + locationPath.VEHICLE_URL + locationPath.SEARCH_URL + param)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
        return promise;
    }

    return vehicleFactory;
}]);