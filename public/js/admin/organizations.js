app.controller('ctrlOrg',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.organizations = init.organizations;
  $scope.catList = init.catList;
  $scope.orgList = init.orgList;
  $scope.o = {};
  $scope.target = null;
  $('[data-toggle="popover"]').popover();
  
  $scope.printDataInfo = printDataInfo;
  $scope.momenter = function(d) {
    return moment(d).calendar();
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit category: " + o.category + ", organization: " + o.name;
    $scope.o = angular.copy(o);
    $("#modalEdit").modal('show');
  };
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Category/Organization";
    $scope.o = {category:'',name:'',description:''};
    $("#modalEdit").modal('show');
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post("/admin/organizations",oPost, function(rep) {
      $scope.organizations.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target,token:token};
    tr.post("/admin/organizations",oPost, function(rep) {
      $scope.target.category = rep.o.category;
      $scope.target.name = rep.o.name;
      $scope.target.description = rep.o.description;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Category: " + o.category + "\nOrganization: " + o.name) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post("/admin/organizations",oPost, function(rep) {
      arrRemoveElement($scope.organizations,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
}]);
