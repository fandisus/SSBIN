app.controller('ctrlIndices',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.classes = init.classes;
  $scope.districts = init.districts;
  $scope.landcovers = init.landcovers;
  $scope.months = init.months;
  
  var uri = "/indices";
  
  $scope.rows = [];
  $scope.params = {};
  $scope.fields = [
    {key:"class", text:'Class'},
    {key:"family", text:'Family'},
    {key:"genus", text:'Genus'},
    {key:'surveydate', text:'Survey Date'},
    {key:'district', text:'District'},
    {key:'landcover', text:'Landcover'},
  ];
  $scope.filter = null;
  $scope.addFilter = function(f) {
    $('#'+f.key).css('display','');
    if (f.key==='surveydate') $scope.params.startDate=null,$scope.params.endDate=null;
    else $scope.params[f.key]=null;
  };
  $scope.removeFilter = function(e) {
    var row = e.delegateTarget.parentElement.parentElement;
    row.style.display='none';
    var key = row.getAttribute('id');
    if (key==='surveydate') delete $scope.params.startDate,delete $scope.params.endDate;
    else delete $scope.params[key];
  };
  $scope.showIndices = function() {
    tr.post(uri,{a:'calc',params:$scope.params,token:token}, function(rep) {
      $scope.rows = rep.rows;
      $scope.summ = rep.summ;
      $.notify(rep.message,'success');
      $scope.$apply();
    });
  };
}]);