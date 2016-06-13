app.controller('ctrlOrg', function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.organizations = init.organizations;
  $scope.catList = init.catList;
  $scope.orgList = init.orgList;
  $scope.o = {};
  $scope.target = null;
  $('[data-toggle="popover"]').popover();
  
  $scope.printDataInfo = function(o) {
    if (o.data_info == undefined) return '';
    return "Created By: " + o.data_info.created_by +
            "<br />Created At: " + moment(o.data_info.created_at).calendar() + 
            "<br />Updated By: " + o.data_info.updated_by +
            "<br />Updated At: " + moment(o.data_info.updated_at).calendar();
  };
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
    $scope.o = {category:'',name:''};
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
      $scope.organizations.remove($scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
});
