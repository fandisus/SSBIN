<?php
$pageTitle = "Password";
$pageSubTitle = "Ganti Password";
include $template;
function htmlHead() { ?>
<script>
  app.controller('ctrlPass',function($scope) {
    token = '<?= \Trust\Server::csrf_token(); ?>';
    var timeout;
    $scope.username = '';
    $scope.pass = {oldpass:'',newpass:'',repass:''};
    $scope.ubah = function() {
      if ($scope.pass.newpass !== $scope.pass.repass) {
        Example.show("Konfirmasi password tidak sama", "danger"); return;
      }
      oPost = {a:'pass',pass:$scope.pass,token:token};
      tr.post("/users/password",oPost,function(rep) {
        Example.show("Password telah diubah","success");
      });
    };
    $scope.userChanged = function() {
      clearTimeout(timeout);
      if ($scope.username == '') return;
      timeout = setTimeout($scope.cekUser,1000);
    };
    $scope.cekUser = function() {
      clearTimeout(timeout);
      tr.post("/users/password",{a:'cekUser',user:$scope.username,token:token},function(rep) {
        if (rep.res === "OK") {
          $scope.userMessage = "Username OK"; $scope.msgClass="text-success";
        } else {
          $scope.userMessage = rep.message; $scope.msgClass="text-danger";
        }
        $scope.$apply();
      });
    };
    $scope.changeUser = function() {
      clearTimeout(timeout);
      tr.post("/users/password",{a:'setUser',user:$scope.username,token:token},function(rep) {
        Example.show("Username telah diubah","success");
        $("#panelUser").html('');
      });
    };
  });
</script>
<?php }

function mainContent() { global $login; ?>
<div class="row" ng-controller="ctrlPass">
  <div class="col-md-4 col-md-offset-4">
    <?php if ($login->kontak->fbid) { ?>
    <div class="alert alert-info flex flex-vcenter">
      <i class="fa fa-info-circle fa-2x" style="margin: 0 5px 0 0;"></i>
      Anda tidak memerlukan username dan password bila login dengan facebook
    </div>
    <?php if (!$login->username) { ?>
    <div class='panel panel-info' id='panelUser'>
      <div class="panel-heading"><h3 class="panel-title">Buat username</h3></div>
      <div class='panel-body'>
        <i class="fa fa-info-circle"></i> Username hanya dapat dipilih satu kali
        <form>
          <div class='form-group'>
            <input type='text' class='form-control' placeholder="Username" ng-model="username" ng-change='userChanged()' />
            <span ng-class="msgClass">{{userMessage}}</span>
          </div>
          <button ng-click='cekUser()' class='btn btn-info'>Cek Username</button>
          <button ng-click='changeUser()' class='btn btn-success'>Pakai username</button>
        </form>
      </div>
    </div>
    <?php } } ?>
    <div class="panel panel-default">
      <div class='panel-heading'>
        <h3 class="panel-title">Ubah Password</h3>
      </div>
      <div class='panel-body'>
        <form>
          <div class="form-group">
            <label for="oldpass">Password Lama</label>
            <input type="password" class="form-control" ng-model="pass.oldpass" placeholder="Password lama" />
            <span class='text-info'>Kosongkan bila belum ada</span>
          </div>
          <div class="form-group">
            <label for="newpass">Password Baru</label>
            <input type="password" class="form-control" ng-model="pass.newpass" placeholder="Password baru" />
          </div>
          <div class="form-group">
            <label for="repass">Ulangi Password</label>
            <input type="password" class="form-control" ng-model="pass.repass" placeholder="Ulangi password baru" />
          </div>
          <button ng-click="ubah()" class='btn btn-primary'>Ubah Password</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php }
