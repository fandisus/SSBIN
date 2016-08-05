app.controller('ctrlIUCN',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.statuses = init.statuses;
  var uri = "/admin/lookup/iucn";
  $scope.pager = {uri:uri, totalItems:init.totalItems}
  $scope.pager.filterOptions = [{key:'abbr',text:'Abbreviation'},{key:'long_name',text:'IUCN Status'}];
  $scope.pager.orderOptions = [{key:'abbr',text:'Abbreviation'},{key:'long_name',text:'IUCN Status'}];
  $scope.pager.pageChanged = function(rep) {
    $scope.statuses = rep.statuses;
    $scope.$apply();
  };
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $('[data-toggle="popover"]').popover();
  
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New IUCN Status";
    $scope.o = {abbr:'',long_name:''};
    $("#modalEdit").modal('show');
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit IUCN Status: " + o.long_name + '(' + o.abbr + ')';
    $scope.o = angular.copy(o);
    $("#modalEdit").modal('show');
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.statuses.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.target.abbr = rep.o.abbr;
      $scope.target.long_name = rep.o.long_name;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\IUCN Status: " + o.long_name) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.statuses,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
  $scope.oChanged = function() {
    console.log($scope.o);
  };
}]);
