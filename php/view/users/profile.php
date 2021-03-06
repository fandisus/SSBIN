<?php
$pageTitle = "Profil";
$pageSubTitle = "Set profil";
include $template;

function htmlHead() {
  ?>
  <style>
    .file-input img {max-height: 300px; max-width: 300px;}
  </style>
  <script src="/jslib/mine/file-input.js"></script>
  <script src="/jslib/moment.min.js"></script>
  <script>token = '<?= \Trust\Server::csrf_token() ?>';</script>
  <script>
    app.controller('ctrlProfil', function ($scope) {
      var init = JSON.parse($("#init").html());
      //$("#init").html('');
      $("#token").val(token);
      $scope.biodata = init.biodata;
      $scope.biodata.dob = (init.biodata.dob == null) ? null : new Date($scope.biodata.dob);
      $scope.img_profile = init.img_profile;
      $scope.category = init.category;
      $scope.organization = init.organization;
      $scope.addPhone = function () {
        $scope.biodata.phone.push('');
      };
      $scope.saveBiodata = function () {
        var oPost = {a: "biodata", biodata: $scope.biodata, token: token};
        tr.post("/users/profile", oPost, function (rep) {
          $.notify("Biodata has been saved", "success");
          $scope.biodata = rep.biodata;
          $scope.$apply();
        });
      };
      $scope.savePP = function () {
        tr.postForm("/users/profile", $("#form-PP")[0], function (rep) {
          $.notify("Profile Pic successfully changed", "success");
          $scope.img_profile = rep.img_profile;
          $scope.$apply();
        });
      };
      $scope.nullPP = function () {
        var oPost = {a: "nullPP", token: token};
        tr.post("/users/profile", oPost, function (rep) {
          $.notify("Profile pic changed to default.", "success");
          $scope.img_profile = rep.img_profile;
          $scope.$apply();
        });
      };
    });
  </script>
<?php }

function mainContent() {
  global $login;
  ?>
  <div class="row" ng-controller="ctrlProfil">
    <div class="col-md-offset-3 col-sm-6">
      <div class="panel panel-default">
        <div class="panel-body">
          <h2>User Profile</h2>
          <h4>Category: {{category}}<br />Organization: {{organization}}</h4>
          <!--Profile Picture-->
          <form id='form-PP' enctype="multipart/form-data" class="panel panel-default">
            <div class="panel-body">
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
              <button ng-click='nullPP()' class='btn btn-default'>Pakai PP Default</button>
            </div>
          </form>
          <!--End Profile Picture-->
          <!--Biodata-->
          <form class="panel panel-default">
            <div class="panel-body">
              <div class="form-group">
                <label for="name" class="control-label">Full Name</label>
                <input type="text" class="form-control" id="name" ng-model="biodata.name"/>
              </div>
              <div class="form-group">
                <label for="gender" class="control-label">Gender</label>
                <select name="gender" class="form-control" id="gender" ng-options="a as a for a in ['Male','Female']" ng-model="biodata.gender">
                  <option value="">-- Gender --</option>
                </select>
              </div>
              <hr />
              <div class="form-group">
                <label for="dob" class="control-label">DOB</label>
                <input type="date" name="tanggal_lahir" class="form-control" id="dob" ng-model="biodata.dob" placeholder="yyyy-mm-dd" />
              </div>
              <div class="form-group">
                <label for="city" class="control-label">City</label>
                <input type="text" class="form-control" id="city" ng-model="biodata.city" />
              </div>
              <div class="form-group">
                <label for="state" class="control-label">State</label>
                <input type="text" class="form-control" id="state" ng-model="biodata.state" />
              </div>
              <div class="form-group">
                <label for="phone" class="control-label">
                  Phone <button class="btn btn-success" ng-click="addPhone()"><i class="fa fa-plus"></i></button></label>
                <input type="text" ng-repeat="a in biodata.phone track by $index" ng-model="biodata.phone[$index]" class="form-control"/>
              </div>
              <button ng-click="saveBiodata()" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
            </div>
          </form>
          <!--End Biodata-->
        </div>
      </div>
    </div>
    <div id='init' style='display:none'><?php
  $init = new stdClass();
  $init->biodata = json_decode(json_encode($login->biodata));
  $init->category = $login->category;
  $init->organization = $login->organization;
  $init->img_profile = $login->profilePic();
  echo json_encode($init);
  ?></div>
  </div>
<?php
}
