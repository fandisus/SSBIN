<?php
include DIR."/php/view/tulang.php";
//TODO: Catat not found uri ke database
function mainContent() { ?>
<div class="col-md-offset-4 col-md-4 col-xs-12" style="text-align: center;">
  <h2>Halaman tidak ditemukan</h2>
  <i class="fa fa-money"></i>
  <p>Mohon maaf, halaman yang Anda cari tidak dapat kami sajikan. Kesalahan ini telah kami catat untuk diselidiki lebih lanjut</p>
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
