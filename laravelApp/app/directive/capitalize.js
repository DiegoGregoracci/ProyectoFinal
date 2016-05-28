// Esta directiva hace que un INPUT sea forzado a usar mayusculas
app.directive('capitalize', function() {
    return {
        require: 'ngModel',
        link: function(scope, element, attrs, modelCtrl) {
          var capitalize = function(inputValue) {
            if (inputValue == undefined) inputValue = '';
            var capitalized = inputValue.toUpperCase();
            if (capitalized !== inputValue) {
              modelCtrl.$setViewValue(capitalized);
              modelCtrl.$render();
            }
            return capitalized;
          }
          modelCtrl.$parsers.push(capitalize);
          capitalize(scope[attrs.ngModel]); // capitalize initial value
        }
    };
});