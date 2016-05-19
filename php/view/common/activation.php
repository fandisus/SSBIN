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

function kode_tak_ditemukan() { ?>
<h2>Kode Tidak Ditemukan</h2>
Mohon maaf, sepertinya link yang anda klik bermasalah.  
<?php }


function kode_sudah_expired() { global $p; ?>
<script>
  var resend = function() {
    oPost = {kode:'<?= $p->login_info->kode_aktivasi ?>', a:'resend', token:'<?= \Trust\Server::csrf_token() ?>'};
    tr.post("/aktivasi",oPost,function(r) {
      $('#pesan').html(r.message);
    });
  };
</script>
<h2>Link aktivasi expired</h2>
<div id="pesan">Maaf, link aktivasi akun yang Anda pakai telah expired.<br /><br />
<button class="btn btn-primary btn-lg" onclick="resend()">Kirim ulang link aktivasi</button>
</div>
<?php }


function aktivasi_berhasil() {
  $p = $GLOBALS['p'];
?>
    <h2>Akun telah diaktifkan</h2>
    <div class="col-xs-12" style="margin-bottom: 15px;">
      Selamat <b><?= $p->kontak->nama ?></b> akun Anda telah aktif.<br />
      Untuk selanjutnya, Silahkan:
    </div>
    <a class="img-link" href="/"><div class="col-xs-5">
      <img src="/images/empty-shopping-cart-icon.png" alt="Belanja"/>
      <h3>Berbelanja</h3>
    </div></a>
    <a class="img-link" href="/users/toko"><div class="col-xs-5 col-xs-offset-1">
      <img src="/images/shop-icon s.png" alt="Buka Toko"/>
      <h3>Buka Toko</h3>
    </div></a>
<?php }
function get_is_empty() {
  echo "Link aktivasi error. Silahkan kembali ke <a href=\"/\">home</a>";
}
