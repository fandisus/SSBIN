<?php
include DIR."/php/view/tulang.php";
//TODO: Catat not found uri ke database
function mainContent() { ?>
<div class="col-md-offset-4 col-md-4 col-xs-12" style="text-align: center;">
  <h2>Forbidden Access</h2>
  <p>We're sorry, you are not authorized to access the url. Please contact the site administrator if you think this is not right.</p>
  <table class="table table-bordered table-responsive table-striped">
    <tr>
      <td>Uri</td>
      <td><?= DOMAIN.$_SERVER['REQUEST_URI'] ?></td>
    </tr>
    <tr>
      <td>IP Address</td>
      <td><?= $_SERVER['REMOTE_ADDR'] ?></td>
    </tr>
  </table>
</div>
<?php }
