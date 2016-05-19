//From: http://stackoverflow.com/questions/18754029/whats-wrong-with-my-angularjs-directive
app.directive('contenteditable',function() { return {
    require: 'ngModel',
    link: function(scope, element, attrs, ctrl) {
        // view -> model
        element.bind('input', function() {
            scope.$apply(function() {
                ctrl.$setViewValue(element["0"].tagName=="INPUT" ? element.val() : element.text());
                //scope.watchCallback(element.attr('data-ng-model'));
            });
          });
        // model -> view
        ctrl.$render = function() {
            element.text(ctrl.$viewValue);
            element.val(ctrl.$viewValue);
        };
     }};
});
