app.controller("ctrlUsers",function($scope) {
  var init = JSON.parse($("#init").html());
  $("#init").html("");
  $scope.users = init.users;
  $scope.levels = init.levels;
  $scope.categories = init.categories;
  $scope.organizations = [];
  $scope.classes = init.classes;
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
  $scope.editExpertise = function(u) {
    $scope.u = u;
    $scope.editUser = angular.copy(u);
    $("#modalExpertise").modal('show');
  };
  $scope.remExpertise = function(e) {
    arrRemoveElement($scope.editUser.expertise,e);
  };
  $scope.addClass = function(c) {
    if (c == undefined) return;
    if ($scope.editUser.expertise.indexOf(c) == -1) $scope.editUser.expertise.push(c);
  };
  $scope.saveExpertise = function(eu) {
    var obj = {id:eu.id,expertise:eu.expertise};
    var oPost = {a:'saveExpertise',u:obj, token:token};
    tr.post("/admin/users",oPost,function(rep) {
      $scope.u.expertise = rep.o.expertise;
      $scope.u.data_info = rep.o.data_info;
      $scope.$apply();
      
      $("#modalExpertise").modal('hide');
      $.notify(rep.message,'success');
    });
  };
  $scope.saveChanges = function(eu) {
    var obj = {id:eu.id,level:eu.level,category:eu.category,organization:eu.organization};
    var oPost = {a:'save',u:obj,token:token};
    tr.post("/admin/users",oPost,function(rep) {
      $scope.u.level = rep.o.level;
      $scope.u.category = rep.o.category;
      $scope.u.organization = rep.o.organization;
      $scope.u.data_info = rep.o.data_info;
      $scope.$apply();
      
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
    });
  };
});
