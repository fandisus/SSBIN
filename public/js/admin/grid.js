app.controller('ctrlGrid',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.grids = init.grids;
  var uri = "/admin/lookup/grid";
  $scope.pager = {uri:uri, totalItems:init.totalItems}
  $scope.pager.filterOptions = [{key:'grid',text:'Grid'}];
  $scope.pager.orderOptions = [{key:'grid',text:'Grid'}];
  $scope.pager.pageChanged = function(rep) {
    $scope.grids = rep.grid;
    $scope.$apply();
  };
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $('[data-toggle="popover"]').popover();
  
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Grid";
    $scope.o = {grid:''};
    $("#modalEdit").modal('show');
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit grid: " + o.grid;
    $scope.o = angular.copy(o);
    $("#modalEdit").modal('show');
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.grids.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.target.grid = rep.o.grid;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Grid: " + o.location) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.grids,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
  $scope.oChanged = function() {
    console.log($scope.o);
  };
}]);
