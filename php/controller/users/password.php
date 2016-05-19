<?php
if (!count($_POST)) { include DIR."/php/view/users/password.php"; die(); }

use Trust\JSONResponse;
use Solaris\Pengguna;
use Trust\Basic;
$services = ['pass','setUser','cekUser'];
if (in_array($_POST['a'], $services)) $_POST['a']();

function pass() { global $login;
  $pass = json_decode(json_encode($_POST['pass']));
  $p = Pengguna::find($login->id, "id,password,data_info");
  if ($p->password && hash('sha256',$pass->oldpass) != $p->password) JSONResponse::Error("Password lama tidak benar");
  if ($pass->newpass != $pass->repass) JSONResponse::Error ("Konfirmasi password tidak sama");
  $err = Basic::validatePass($pass->newpass);
  if (count($err)) JSONResponse::Error(implode("<br />", $err));
  
  $p->password = hash('sha256', $pass->newpass);
  $p->save();
  JSONResponse::Success();
}

function cekUser() {global $login;
  if ($login->username) JSONResponse::Error("Username tidak dapat diganti");
  $err = Pengguna::validateUsername($_POST['user']);
  if (count($err)) JSONResponse::Success(["res"=>"not OK","message"=>$err[0]]);
  JSONResponse::Success(['res'=>'OK']);
}

function setUser() { global $login;
  if ($login->username) JSONResponse::Error("Username tidak dapat diganti");
  $err = Pengguna::validateUsername($_POST['user']);
  if (count($err)) JSONResponse::Error(implode("<br />",$err));
  $p = Pengguna::find($login->id, "id,username,data_info");
  $login->username = $p->username = $_POST['user'];
  $p->save();
  
  $login->data_info= $p->data_info;
  $_SESSION['login'] = $login;
  
  JSONResponse::Success(['res'=>'OK']);
}
