app.controller('ctrlFamily',['$scope',function($scope) {
  var uri = "/expert/taxonomies/families";
  var init = JSON.parse($("#init").html());
  $scope.families = init.families;
  $scope.familyList = []; //load via ajax
  
  $scope.pager = {uri:uri, totalItems:init.totalItems}
  $scope.pager.filterOptions = [{key:'family',text:'Family'}];
  $scope.pager.orderOptions = [{key:'family',text:'Family'}];
  $scope.pager.pageChanged = function(rep) {
    $scope.families = rep.families;
    $scope.$apply();
  };
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $('[data-toggle="popover"]').popover();
  
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Familiy";
    $scope.o = {family:''};
    $("#modalEdit").modal('show');
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit family: " + o.family;
    $scope.o = angular.copy(o);
    $("#modalEdit").modal('show');
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.families.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.target.family = rep.o.family;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Family: " + o.family) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.families,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
  $scope.oChanged = function() {
    console.log($scope.o);
  };
}]);
