app.controller('ctrlReg',['$scope',function($scope) {
  var timeout, timeout2;
  $scope.user = {email:'',username:'',name:'',pass:'',repass:'',gender:'',category:'',organization:''};
  var init = JSON.parse($("#init").html()); $("#init").html("");
  $scope.categories = init.categories;
  $scope.organizations = [];
  $scope.tryRegister = function() {
    clearTimeout(timeout); clearTimeout(timeout2);
    oPost = {a:'register',user:$scope.user,token:token};
    tr.post("/register",oPost,function(rep) {
      $.notify(rep.message);
      setTimeout(function() {window.location = "/login";}, 5000);
    }, function (rep) {
      if (rep.email_error) $(".forgot-link").show(1000);
      else $(".forgot-link").hide(1000);
    });
  };
  $scope.updateOrganizationSelect = function() {
    $scope.organizations = $scope.categories[$scope.user.category];
  };
  $scope.emailChanged = function() {
    clearTimeout(timeout2);
    if ($scope.email == "") return;
    timeout2 = setTimeout($scope.checkEmail,1000);
  };
  $scope.userChanged = function() {
    clearTimeout(timeout);
    if ($scope.username == '') return;
    timeout = setTimeout($scope.checkUsername,1000);
  };
  $scope.checkUsername = function() {
    clearTimeout(timeout);
    oPost = {a:"checkUsername",user:$scope.user.username,token:token};
    tr.post("/register",oPost,function(rep) {
      $("#user").notify("Username OK",{position:"top left"});
      $scope.$apply();
    }, function(rep) {
      $("#user").notify(rep.message,{position:"top left",className:"error"});
    });
  };
  $scope.checkEmail = function() {
    clearTimeout(timeout2);
    oPost = {a:"checkEmail",email:$scope.user.email,token:token};
    tr.post("/register",oPost,function(rep){
      $("#email").notify("Email OK",{position:"top left"});
      $(".forgot-link").hide(1000);
    }, function(rep) {
      $("#email").notify(rep.message,{position:"top left",className:"error"});
      $(".forgot-link").show(1000);
    });
  };
}]);
