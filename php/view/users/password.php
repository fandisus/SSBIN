<?php
$pageTitle = "Password";
$pageSubTitle = "Ganti Password";
include $template;
function htmlHead() { ?>
<script>token = '<?= \Trust\Server::csrf_token(); ?>';</script>
<script>
  app.controller('ctrlPass',function($scope) {
    $scope.pass = {oldpass:'',newpass:'',repass:''};
    $scope.ubah = function() {
      if ($scope.pass.newpass !== $scope.pass.repass) {
        $.notify("Password confirmation invalid", "error"); return;
      }
      oPost = {a:'pass',pass:$scope.pass,token:token};
      tr.post("/users/password",oPost,function(rep) {
        $.notify(rep.message);
      });
    };
  });
</script>
<?php }

function mainContent() { global $login; ?>
<div class="row" ng-controller="ctrlPass">
  <div class="col-md-4 col-md-offset-4">
    <div class="panel panel-default">
      <div class='panel-heading'>
        <h3 class="panel-title">Change password</h3>
      </div>
      <div class='panel-body'>
        <form>
          <div class="form-group">
            <label for="oldpass">Old Password</label>
            <input type="password" class="form-control" ng-model="pass.oldpass" placeholder="Old Password" />
          </div>
          <div class="form-group">
            <label for="newpass">New Password</label>
            <input type="password" class="form-control" ng-model="pass.newpass" placeholder="New Password" />
          </div>
          <div class="form-group">
            <label for="repass">Repeat Password</label>
            <input type="password" class="form-control" ng-model="pass.repass" placeholder="Repeat new password" />
          </div>
          <button ng-click="ubah()" class='btn btn-primary'>Change password</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }
