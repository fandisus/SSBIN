<?php
//TODO: Register, Login dan Forgot mungkin ndak perlu tulang.php, tapi tulang yang lebih sederhana.
include DIR.'/php/view/tulang.php';
function htmlHead() { ?>
<?php }

function mainContent() { global $viewMode, $login;
  if (!isset($viewMode) && !isset($login)) askEmail();
  elseif (!isset($viewMode) && isset($login)) tellHimToLogout();
  elseif ($viewMode == "ResetPass") newPassword();
  elseif ($viewMode == "TokenNotFound") showError();
  else echo "IMPOSSIBLE!!! how did you got here?";
}

function newPassword() { ?>
<script>
  var kirim = function() {
    var token = '<?= \Trust\Server::csrf_token() ?>';
    var pass = document.getElementById('pass').value;
    var repass = document.getElementById('repass').value;
    tr.post('/forgot',{a:'password',pass:pass,repass:repass,token:token}, function(rep){
      $("#pesan").html(rep.msg);
      $("#btn").prop('disabled',true);
      setTimeout(function() {window.location="/users"}, 5000);
    });
  }
</script>
<div class="row">
  <div class="col-sm-4 col-sm-offset-4">
    <form>
      <div class="form-group">
        <label for="pass">New Password</label>
        <input type="password" id="pass" class="form-control" placeholder="New Password"/>
      </div>
      <div class="form-group">
        <label for="repass">Repeat Password</label>
        <input type="password" id="repass" class="form-control" placeholder="Confirm new password"/>
      </div>
      <button id="btn" onclick="kirim()" class="btn btn-success">Send</button>
    </form>
    <div id="pesan"></div>
  </div>
</div>
<?php }

function showError() { ?>
<div class="row">
  <div class="col-sm-4 col-sm-offset-4">
    <p>Sorry, the link you followed is no longer valid.</p>
    <p>You might want to request a new forget password email from <a href="/forgot">this page.</a></p>
  </div>
</div>
<?php }

function askEmail() { ?>
<script>
  var kirim = function() {
    var token = '<?= \Trust\Server::csrf_token() ?>';
    var email = document.getElementById('email').value;
    tr.post('/forgot',{a:'email',email:email,token:token}, function(rep){
      $("#pesan").html(rep.msg);
    });
  }
</script>
<div class="row">
  <div class="col-sm-4 col-sm-offset-4">
    <form>
      <div class="form-group">
        <label for="email">Please input E-mail</label>
        <input type="email" id="email" class="form-control" placeholder="Your account's email"/>
      </div>
      <button onclick="kirim()" class="btn btn-success">Send</button>
    </form>
    <div id="pesan"></div>
  </div>
</div>
<?php }

function tellHimToLogout() { ?>
<div class="row">
  <div class="col-sm-4 col-sm-offset-4">
    Please, <a href="/logout">logout</a> first before using this feature.
  </div>
</div>
<?php }
