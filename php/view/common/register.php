<?php
if (isset($login)) header("location:/");

include DIR."/php/view/tulang.php";
function htmlHead() { ?>
<script>token = '<?= \Trust\Server::csrf_token() ?>';</script>
<script src="/js/common/register.js"></script>
<?php }
function mainContent() { global $login; ?>
<div ng-controller="ctrlReg" class="row" style="margin-bottom: 20px;">
  <form class="col-sm-4 col-sm-offset-4">
    <h2>User Registration</h2>
    <div class="form-group">
      <label for="email" class="control-label">E-mail</label>
      <input type="email" id="email" class="form-control" ng-model="user.email" ng-change="emailChanged()" placeholder="E-mail"/>
      <span style="display: none;" class="forgot-link">Apakah Anda <a href="/forgot">lupa password?</a></span>
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
