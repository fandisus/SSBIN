<?php
$pageTitle = "Profil";
$pageSubTitle = "Set profil";
include $template;
function htmlHead() { ?>
<style>
  .mild-cyan{ background: rgba(210,230,255,0.6);}
  .cyan { background: rgba(210,230,255,1)}
  .mild-orange { background: rgba(255,230,210,0.6);}
  .orange { background: rgba(255,230,210,1);}
  
  .file-input img {max-height: 300px; max-width: 300px;}
</style>
<script src="/jslib/mine/file-input.js"></script>
<script src="/jslib/moment.min.js"></script>
<script>
  app.controller('ctrlProfil', function($scope) {
    var token = '<?= \Trust\Server::csrf_token() ?>';
    var login = JSON.parse($("#init").html());
    //$("#init").html('');
    $("#token").val(token);
    $scope.kontak = login.kontak;
    $scope.biodata = login.biodata;
    $scope.biodata.tanggal_lahir = new Date($scope.biodata.tanggal_lahir);
    $scope.img_profile = login.img_profile;
    $scope.addTelepon = function() { $scope.kontak.telepon.push(''); };
    $scope.saveKontak = function() {
      var oPost = {a:"kontak",kontak:$scope.kontak,token:token};
      tr.post("/users/profil",oPost,function(rep) {
        Example.show("Data kontak telah tersimpan","success");
        $scope.kontak = rep.kontak;
        $scope.$apply();
      });
    };
    $scope.saveBiodata = function() {
      var gender = $scope.biodata.gender;
      var tgl =  $scope.biodata.tanggal_lahir;
      tgl = (tgl == null) ? null : moment(tgl).format('YYYY-MM-DD');
      var oPost = {a:"biodata",biodata:{gender:gender,tanggal_lahir:tgl},token:token};
      tr.post("/users/profil",oPost,function(rep) {
        Example.show("Data biodata telah tersimpan","success");
        $scope.biodata = rep.biodata;
        $scope.$apply();
      });
    };
    $scope.savePP = function() {
      tr.postForm("/users/profil",$("#form-PP")[0],function(rep) {
        Example.show("Profile Pic berhasil diubah","success");
        $scope.img_profile = rep.img_profile;
        $scope.$apply();
      });
    };
    $scope.nullPP = function() {
      var oPost = {a:"nullPP",token:token};
      tr.post("/users/profil",oPost,function(rep) {
        Example.show("Foto telah diubah ke foto facebook","success");
        $scope.img_profile = rep.img_profile;
        $scope.$apply();
      });
    };
  });
</script>
<?php }
function mainContent() { global $login; ?>
<div class="row" ng-controller="ctrlProfil">
  <div class="col-sm-4 col-sm-offset-4">
    <div class="panel panel-default">
      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <tr>
            <th>Username</th>
            <td><?= $login->username ?></td>
          </tr>
          <tr>
            <th>E-Mail</th>
            <td><?= $login->kontak->email ?></td>
          </tr>
          <tr>
            <th>Status</th>
            <td><?= ($login->sudah_aktif) ? "Aktif" : "Belum aktivasi email" ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-body mild-cyan">
        <h2>Data Kontak</h2>
        <div class="alert alert-warning flex flex-vcenter">
          <i class="fa fa-info-circle" style="padding: 0 10px 0 0"></i>
          <p>Informasi ini akan dipakai saat Anda berbelanja. Formulir alamat pengiriman secara default akan terisi dengan informasi seperti di bawah.</p>
        </div>
        <form class="panel panel-default">
          <div class="panel-body cyan">
          <div class="form-group">
            <label for="nama" class="control-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" ng-model="kontak.nama"/>
          </div>
          <div class="form-group">
            <label for="kota" class="control-label">Kota</label>
            <input type="text" class="form-control" id="kota" ng-model="kontak.kota" />
          </div>
          <div class="form-group">
            <label for="alamat" class="control-label">Alamat</label>
            <textarea class="form-control" id="alamat" ng-model="kontak.alamat"></textarea>
          </div>
          <div class="form-group">
            <label for="propinsi" class="control-label">Propinsi</label>
            <input type="text" class="form-control" id="propinsi" ng-model="kontak.propinsi" />
          </div>
          <div class="form-group">
            <label for="kelurahan" class="control-label">Kelurahan</label>
            <input type="text" class="form-control" id="kelurahan" ng-model="kontak.kelurahan" />
          </div>
          <div class="form-group">
            <label for="kecamatan" class="control-label">Kecamatan</label>
            <input type="text" class="form-control" id="nama" ng-model="kontak.kecamatan" />
          </div>
          <div class="form-group">
            <label for="kodepos" class="control-label">Kodepos</label>
            <input type="text" class="form-control" id="kodepos" ng-model="kontak.kodepos" />
          </div>
          <div class="form-group">
            <label for="telepon" class="control-label">Telepon <button class="btn btn-success" ng-click="addTelepon()"><i class="fa fa-plus"></i></button></label>
            <input type="text" ng-repeat="a in kontak.telepon track by $index" ng-model="kontak.telepon[$index]" class="form-control"/>
          </div>
            <button ng-click="saveKontak()" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-body mild-orange">
        <h2>Data Biodata</h2>
        <form id='form-PP' enctype="multipart/form-data" class="panel panel-default">
          <div class="panel-body orange">
            <input type="hidden" name="a" value="PP"/>
            <input type="hidden" name="token" value="" id="token"/>
            <div class='form-group'>
              <label for="profile_pic">Profile pic</label>
              <div class='file-input'>
                <img ng-src='{{img_profile}}'/>
                <input type="file" name='profile_pic'/>
              </div>
            </div>
            <button ng-click='savePP()' class='btn btn-success'><i class="fa fa-save"></i> Simpan</button>
            <button ng-click='nullPP()' class='btn btn-default'>Pakai PP Facebook</button>
          </div>
        </form>
        <div class="panel panel-default">
          <div class="panel-body orange">
            <div class="form-group">
              <label for="gender" class="control-label">Jenis Kelamin</label>
              <select name="gender" class="form-control" id="gender" ng-options="a as a for a in ['Pria','Wanita']" ng-model="biodata.gender">
                <option value="">-- Jenis Kelamin --</option>
              </select>
            </div>
            <div class="form-group">
              <label for="tgllahir" class="control-label">Tanggal Lahir</label>
              <input type="date" name="tanggal_lahir" class="form-control" id="tgllahir" ng-model="biodata.tanggal_lahir" placeholder="yyyy-mm-dd" />
            </div>
            <button ng-click="saveBiodata()" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id='init' style='display:none'><?php
  $init = new stdClass();
  $init->kontak = json_decode(json_encode($login->kontak));
  $init->biodata = json_decode(json_encode($login->biodata));
  $init->img_profile = $login->profilePic();
  echo json_encode($init);
  ?></div>
</div>
<?php }
