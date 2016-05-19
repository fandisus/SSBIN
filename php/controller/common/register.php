<?php
if (!count($_POST)) { include DIR."/php/view/common/register.php"; die(); }

use SSBIN\User;
use Trust\JSONResponse;
use Trust\Basic;
$services = ["register","checkUsername"];
if (in_array($_POST['a'], $services)) $_POST['a']();

function register() {
  $user = json_decode(json_encode($_POST['user']));
  //email, nama, username, pass, repass, gender
  if (!Basic::validateEmail($user->email)) JSONResponse::Error("Email tidak valid");
  $p = Pengguna::findByEmailOrUsername($user->email,"id");
  if ($p != null) JSONResponse::Error("Email tersebut sudah terpakai.",["email_error"=>true]);
  if (strlen($user->nama) < 3) JSONResponse::Error("Mohon isi nama lengkap Anda");
  $err = Pengguna::validateUsername($user->username);
  if (count($err)) JSONResponse::Error(implode("<br />",$err));
  $err = Basic::validatePass($user->pass);
  if (count($err)) JSONResponse::Error(implode("<br />",$err));
  if ($user->pass != $user->repass) JSONResponse::Error("Konfirmasi password tidak sama");
  if (!in_array($user->gender, ['Pria','Wanita'])) JSONResponse::Error("Harap isi jenis kelamin Anda");
  
  $p = new Pengguna([
      "username"=>$user->username,
      "password"=>hash('sha256',$user->pass)
      ],true);
  $p->kontak->nama = $user->nama;
  $p->kontak->email = $user->email;
  $p->biodata->gender = $user->gender;
  $p->login();
  $p->sendActivationEmail();
  
  JSONResponse::Success();
}
function checkUsername() {global $login;
  $err = Pengguna::validateUsername($_POST['user']);
  if (count($err)) JSONResponse::Error(implode("\n",$err));
  JSONResponse::Success();
}
