<?php
include DIR."/php/view/tulang.php";
//TODO: Catat not found uri ke database
function mainContent() { ?>
<div class="col-md-offset-4 col-md-4 col-xs-12" style="text-align: center;">
  <h2>Page Not Found</h2>
  <p>We're sorry, the url you requested is not available. We've noted this error to be examined later.</p>
  <table class="table table-bordered table-responsive table-striped">
    <tr>
      <td>Uri</td>
      <td><?= DOMAIN.$_SERVER['REQUEST_URI'] ?></td>
    </tr>
    <tr>
      <td>IP Address</td>
      <td><?= $_SERVER['REMOTE_ADDR'] ?></td>
    </tr>
    <?php foreach (get_browser() as $k=>$v) { ?>
    <tr>
      <td><?= $k ?></td>
      <td><?= $v ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php }
