app.controller('ctrlLocation',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.locations = init.locations;
  var uri = "/admin/lookup/location";
  $scope.pager = {uri:uri, totalItems:init.totalItems}
  $scope.pager.filterOptions = [{key:'location',text:'Location'}];
  $scope.pager.orderOptions = [{key:'location',text:'Location'}];
  $scope.pager.pageChanged = function(rep) {
    $scope.locations = rep.location;
    $scope.$apply();
  };
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $('[data-toggle="popover"]').popover();
  
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Location";
    $scope.o = {location:''};
    $("#modalEdit").modal('show');
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit location: " + o.location;
    $scope.o = angular.copy(o);
    $("#modalEdit").modal('show');
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.locations.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.target.location = rep.o.location;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Location: " + o.location) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.locations,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
  $scope.oChanged = function() {
    console.log($scope.o);
  };
}]);
