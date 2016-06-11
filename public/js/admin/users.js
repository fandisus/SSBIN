app.controller("ctrlUsers",function($scope) {
  var init = JSON.parse($("#init").html());
  $("#init").html("");
  $scope.users = init.users;
  $scope.levels = init.levels;
  $scope.categories = init.categories;
  $scope.organizations = [];
  $scope.u = {};
  $scope.editUser = {};
  $scope.updateOrganizationSelect = function() {
    $scope.organizations = $scope.categories[$scope.editUser.category];
  };
  $scope.toggleValidation = function(u) {
    var oPost = {a:"toggleValidation",id:u.id,token:token};
    tr.post("/admin/users",oPost,function(rep) {
      u.validated = rep.validated;
      $scope.$apply();
    });
  };
  $scope.momenter = function(d) {
    return moment(d).calendar();
  };
  $scope.phoneLists = function(u) {
    if (u.biodata == undefined) return;
    var res = "";
    for (var i in u.biodata.phone) {
      res += u.biodata.phone[i] + "\n";
    }
    return res;
  };
  $scope.show = function(u) {
    $scope.u = u;
    $("#modalShowUser").modal('show');
  };
  $scope.edit = function(u) {
    $scope.u = u;
    $scope.editUser = angular.copy(u);
    $scope.updateOrganizationSelect();
    $("#modalEdit").modal("show");
  };
  $scope.saveChanges = function(eu) {
    var obj = {id:eu.id,level:eu.level,category:eu.category,organization:eu.organization};
    var oPost = {a:'save',u:obj,token:token};
    tr.post("/admin/users",oPost,function(rep) {
      $scope.u.level = rep.o.level;
      $scope.u.category = rep.o.category;
      $scope.u.organization = rep.o.organization;
      $scope.$apply();
      
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
    });
  };
});
