app.controller("ctrlUsers",['$scope',function($scope) {
  var uri = "/expert/users";
  var init = JSON.parse($("#init").html());
  $("#init").html("");
  $scope.users = init.users;
  $scope.toggleValidation = function(u) {
    var oPost = {a:"toggleValidation",id:u.id,token:token};
    tr.post(uri,oPost,function(rep) {
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
  $scope.activationEmail = function(id) {
    tr.post(uri,{a:'activationEmail',id:id,token:token}, function(rep) {
      $.notify(rep.message,'success');
    });
  };
}]);
