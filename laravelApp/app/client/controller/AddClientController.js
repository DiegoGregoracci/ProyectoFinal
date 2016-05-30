// Add client controller.
app.controller("AddClientController", ["$scope", "clientFactory", "$location", "$filter", function($scope, clientFactory, $location, $filter) {
    /*
        Initialize & reset object form.
    */
    $scope.initialize = function () {
        $scope.client = {
            "name": "",
            "lastname": "",
            "telephone1": "",
            "telephone2": "",
            "address": "",
            "email": "",
            "cuit": "",
            "user": ""
            }
    };
    /* 
        Watch. Updates user
        Set $scope.client.user = value of lastname input + name input.
    */
    var updateUsername = function(){
        // Delete any special character.
        // TO DO: Hacerlo servicio
        var normalize = (function() {
          var from = "ãàáäâèéëêìíïîòóöôùúüûÑñÇç", 
              to   = "aaaaaeeeeiiiioooouuuunncc",
              mapping = {};
         
          for(var i = 0, j = from.length; i < j; i++ )
              mapping[ from.charAt( i ) ] = to.charAt( i );
         
          return function( str ) {
              var ret = [];
              for( var i = 0, j = str.length; i < j; i++ ) {
                  var c = str.charAt( i );
                  if( mapping.hasOwnProperty( str.charAt( i ) ) )
                      ret.push( mapping[ c ] );
                  else
                      ret.push( c );
              }      
              return ret.join( '' ).replace(/[^a-z]/g, '');
        }
         
        })();

        var str = "";
        // Check if lastname and name are not undefined. Otherwise there will be an error when the input is empty.
        // If not, then add input value (deleting any blank and lowercase) to the string.
        if (typeof $scope.client.name != "undefined")
            str += $filter('lowercase')($scope.client.name).replace(/ /g,'');  
        if (typeof $scope.client.lastname != "undefined")
            str += $filter('lowercase')($scope.client.lastname).replace(/ /g,'');
        
        if (str.length >= 20)
            str = str.substring(0,19);
        // Reset name on change on inputs.
        $scope.client.user = normalize(str);
    }

    /*
        Add new client
        @param addVehicleNext: 1: redirect to new vehicle when done, 0: redirect to client info
    */
    $scope.add = function(addVehicleNext) {
        // Set control vars
        $scope.loading = true;
        $scope.error = false;
        $scope.success = false;
        $scope.errors = [];
        clientFactory.addClient($scope.client).then(function (response) {
                if (response.id) {
                    // If status=200 && ID.
                    if (addVehicleNext)
                        // If addVehicleNext, redirect to next page.
                        $location.path("vehiculo/nuevo/" + response.id);
                    else {
                        // If !addVehicleNext, show success box and reset form.
                        $scope.initialize();
                        $scope.success = true;
                    }
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
                $scope.status = "No se ha podido crear el cliente. Consulte al administrador";
                $scope.loading = false;
                $scope.error = true;
        });
    }
    // Addin watch
    $scope.$watch('client.lastname',updateUsername);
    $scope.$watch('client.name',updateUsername);

    // Control var. True when sending data. Displays loading overlay.
    $scope.loading = false;
    // Control var. True if error adding a client. Shows error box
    $scope.error = false;
    // Control var. True after adding a client. Shows success box
    $scope.success = false; 
    // Initialize form object.
    $scope.initialize();
}]);
