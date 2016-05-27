<?php
if (!count($_POST)) { include DIR."/php/view/users/password.php"; die(); }

use Trust\JSONResponse;
use SSBIN\User;
use Trust\Basic;
$services = ['pass'];
if (in_array($_POST['a'], $services)) $_POST['a']();

function pass() { global $login;
  $pass = json_decode(json_encode($_POST['pass']));
  $p = User::find($login->id, "id,password,data_info");
  if ($p->password && hash('sha256',$pass->oldpass) != $p->password) JSONResponse::Error("Wrong old password ");
  if ($pass->newpass != $pass->repass) JSONResponse::Error ("Konfirmasi password tidak sama");
  $err = Basic::validatePass($pass->newpass);
  if (count($err)) JSONResponse::Error(implode("\n", $err));
  
  $p->password = hash('sha256', $pass->newpass);
  $p->save();
  JSONResponse::Success(["message"=>"Password successfully changed"]);
}
