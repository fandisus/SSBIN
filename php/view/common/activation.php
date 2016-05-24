<?php
include DIR."/php/view/tulang.php";
function htmlHead() { ?>
<style>
  .center-text { text-align: center; }
  .img-link div { display: block; background: #FFA; border-radius:5px; border: 2px solid #FF5; padding: 8px 0;}
  .img-link div:hover { background: #FF0; }
  .img-link div h3 { margin: 0px; margin-top: 5px;}
</style>
<?php }

function mainContent() { global $viewMode;
?><div class="row">
  <div class="col-md-offset-4 col-md-4 col-xs-12 center-text">
    <?php
      if ($viewMode != '') $viewMode();
      else get_is_empty();
    ?>
  </div>
</div><?php 
}

function code_not_found() { ?>
<h2>Code Not Found</h2>
We're sorry, it looks like the link you followed is problematic.
<?php }


function code_has_expired() { global $p; ?>
<script>
  var resend = function() {
    oPost = {code:'<?= $p->login_info->activation_code ?>', a:'resend', token:'<?= \Trust\Server::csrf_token() ?>'};
    tr.post("/activation",oPost,function(r) {
      $('#pesan').html(r.message);
    });
  };
</script>
<h2>Your activation link has expired</h2>
<div id="pesan">We're sorry, the account activation link you followed has been expired.<br /><br />
<button class="btn btn-primary btn-lg" onclick="resend()">Resend activation email</button>
</div>
<?php }


function successful_activation() {
  $p = $GLOBALS['p'];
?>
    <h2>Account has been activated</h2>
    <div class="col-xs-12" style="margin-bottom: 15px;">
      Congratulations <b><?= $p->biodata->name ?></b> Your account is now active.<br />
    </div>
<?php }
function get_is_empty() {
  echo "<h2>Your activation link is invalid.</h2>";
}
