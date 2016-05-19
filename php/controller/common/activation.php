<?php
if (count($_POST)) { handlePost(); die(); }

$viewMode = '';
if (isset($_GET['c'])) {
  $p = \Solaris\Pengguna::findByKodeAktivasi($_GET['c']);
  if ($p == null) $viewMode = 'kode_tak_ditemukan';
  elseif ($p->login_info->kode_berlaku_sampai < time()) $viewMode = "kode_sudah_expired";
  else {
    $viewMode = 'aktivasi_berhasil';
    $p->sudah_aktif = 't';
    unset ($p->password);
    $p->login();
    $_SESSION['login'] = $p;
    $login = $_SESSION['login'];
  }
}
include DIR.'/php/view/common/aktivasi.php'; die();


function handlePost() {
  foreach ($_POST as $k=>$v) $$k = $v;
  if ($a != 'resend') Trust\JSONResponse::Error("Perintah tidak dikenal");
  if (!isset($kode)) Trust\JSONResponse::Error ("Kode tidak terbaca");
  $p = \Solaris\Pengguna::findByKodeAktivasi($kode);
  if ($p == null) Trust\JSONResponse::Error ("Pengguna tidak ditemukan");
  
  $p->login_info->kode_aktivasi = hash('haval192,5',time());
  $p->login_info->kode_berlaku_sampai = (time()+$p::$lama_berlaku*84600);
  unset($p->password, $p->data_info);
  $p->save();
  $p->sendActivationEmail();
  Trust\JSONResponse::Success(["message"=>"Kode telah dikirim ulang. Harap cek kembali email Anda"]);
}
