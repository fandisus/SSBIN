<?php
include DIR."/php/view/tulang.php";
function htmlHead() { ?>
  <!-- FB Login -->
  <script> token = '<?= \Trust\Server::csrf_token() ?>'; </script>
  <script>
    app.controller('login',function($scope) {
      $scope.user = {login:"",pass:"",remember:false};
      $scope.tryLogin = function() {
        oPost = {a:"login", user:$scope.user,token:token};
        tr.post("/login", oPost, function(res) {
          window.location = "/users";
        });
      };
    });
 </script>
  <style>
    main .card { text-align: center; padding: 10px; margin-top: 20px;}
    main .btn { width: 100%;}
    main .align-right {text-align:right;}
    main .btn-info {margin: 8px 0;}
  </style>
<?php }

function mainContent() { ?>
<div class="row" ng-controller="login">
  <div class="col-md-4 col-xs-12 col-md-offset-4 card">
    <form>
      <div class="form-group form-group-lg">
        <input type="text" class="form-control" ng-model="user.login" placeholder="Username Or Email"/>
      </div>
      <div class="form-group form-group-lg">
        <input type="password" class="form-control" ng-model="user.pass" placeholder="Password"/>
      </div>
      <div class="align-right">
        <input type="checkbox" ng-model="user.remember" /> Remember me
      </div>
      <button class="btn btn-info btn-lg" ng-click="tryLogin()">Login</button>
      <div class="align-right">
        <a href="/forgot">Forgot password?</a>
      </div>
    </form>
    <hr />
    <div class="align-right">
      Does not have an account? <a href="/register">Register Now!</a>
    </div>
  </div>
</div>
       
<?php }
