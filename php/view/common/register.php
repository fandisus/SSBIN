<?php
if (isset($login)) header("location:/");

include DIR."/php/view/tulang.php";
function htmlHead() { ?>
<script>token = '<?= \Trust\Server::csrf_token() ?>';</script>
<script>
  app.controller('ctrlReg',function($scope) {
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
        $.notify(rep.message,"error");
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
    }
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
        $("#user").notify(rep.message,{position:"top left"});
      });
    };
    $scope.checkEmail = function() {
      clearTimeout(timeout2);
      oPost = {a:"checkEmail",email:$scope.user.email,token:token};
      tr.post("/register",oPost,function(rep){
        $("#email").notify("Email OK",{position:"top left"});
        $(".forgot-link").hide(1000);
      }, function(rep) {
        $("#email").notify(rep.message,{position:"top left"});
        $(".forgot-link").show(1000);
      });
    };
  });
</script>
<?php }
function mainContent() { global $login; ?>
<div ng-controller="ctrlReg" class="row" style="margin-bottom: 20px;">
  <form class="col-sm-4 col-sm-offset-4">
    <h2>User Registration</h2>
    <div class="form-group">
      <label for="email" class="control-label">E-mail</label>
      <input type="email" id="email" class="form-control" ng-model="user.email" ng-change="emailChanged()" placeholder="E-mail"/>
    </div>
    <div class="form-group">
      <label for="user" class="control-label">Username</label>
      <input type="text" id="user" class="form-control" ng-model="user.username" ng-change='userChanged()' placeholder="Username"/>
    </div>
    <div class="form-group">
      <label for="pass" class="control-label">Password</label>
      <input type="password" id="pass" class="form-control" ng-model="user.pass" placeholder="Password"/>
    </div>
    <div class="form-group">
      <label for="repass" class="control-label">Confirm Password</label>
      <input type="password" id="repass" class="form-control" ng-model="user.repass" placeholder="Repeat Password"/>
    </div>
    <hr />
    <div class="form-group">
      <label for="name" class="control-label">Full Name</label>
      <input type="text" id="name" class="form-control" ng-model="user.name" placeholder="Full Name"/>
    </div>
    <div class="form-group">
      <label for="gender" class="control-label">Gender</label>
      <select id="gender" class="form-control" ng-model="user.gender" ng-options="g for g in ['Male','Female']">
        <option value="">-- Gender --</option>
      </select>
    </div>
    <div class="form-group">
      <label for="category" class="control-label">Category</label>
      <select id="category" class="form-control" ng-model="user.category"
              ng-options="key as key for (key,value) in categories" ng-change="updateOrganizationSelect()">
        <option value="">-- Group --</option>
      </select>
    </div>
    <div class="form-group">
      <label for="organization" class="control-label">Organization</label>
      <select id="organization" class="form-control" ng-model="user.organization"
              ng-options="o.name as o.name for o in organizations">
        <option value="">-- Organization --</option>
      </select>
    </div>
    <button ng-click='tryRegister()' class='btn btn-success'>Register</button>
    <div class="forgot-link" style="display:none">
      Apakah Anda <a href="/forgot">lupa password?</a>
    </div>
  </form>
</div>


<div id="init"><?php
$init = new stdClass();
$init->categories = \SSBIN\Organization::getNestedArray();
echo json_encode($init);
?></div>
<?php }
