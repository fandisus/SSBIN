app.controller("ctrlUsers",['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $("#init").html("");
  $scope.users = init.users;
  $scope.u = {};
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
  $scope.imageIcon=function(u) {
    if (u == undefined || u.biodata == undefined) return '';
    if (u.biodata.profile_pic != null) return icopath + u.biodata.profile_pic;
    if (u.biodata.gender === "Male") return "/images/user-male.png";
    if (u.biodata.gender === 'Female') return "/images/user-female.png";
  };
  $scope.profilePic = function(u) {
    if (u == undefined || u.biodata == undefined) return '';
    if (u.biodata.profile_pic != null) return picpath + u.biodata.profile_pic;
    if (u.biodata.gender === "Male") return "/images/user-male.png";
    if (u.biodata.gender === "Female") return "/images/user-female.png";
  }
}]);
