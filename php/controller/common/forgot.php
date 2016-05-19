<?php
if (!count($_POST) && !isset($_GET['k'])) { include DIR."/php/view/common/forgot.php"; die(); }

use Trust\JSONResponse;
use Trust\Basic;
use Solaris\Pengguna;

$services = ['password','email'];
if (isset($_POST['a']) && in_array($_POST['a'],$services)) $_POST['a']();

if (isset($_GET['k'])) {
  $viewMode = "";
  $p = Pengguna::findByForgotToken($_GET['k']);
  if ($p == null) $viewMode = "TokenNotFound"; //or expired
  else {
    $viewMode = "ResetPass"; //NOTE: Ndak perlu unset forgot_token dan forgot_expiry, ntar jugo expired dewek
    $_SESSION['login'] = $p;
  }
  include DIR."/php/view/common/forgot.php"; die();
}

function email() {
  $email = $_POST['email'];
  if (!Basic::validateEmail($email)) JSONResponse::Error ("Email tidak valid");
  $p = Pengguna::findByEmailOrUsername($email);
  if ($p == null) JSONResponse::Error("Email tidak dikenal");
  
  $p->sendForgotEmail();
  JSONResponse::Success(["msg"=>"E-mail untuk mereset password telah dikirim ke $email.<br />Anda dapat mereset password dengan mengklik link pada email tersebut"]);
}

function password() { global $login;
  $pass = $_POST['pass'];
  $repass = $_POST['repass'];
  if ($pass != $repass) JSONResponse::Error("Password konfirmasi tidak sama dengan password");
  $err = Basic::validatePass($pass);
  if (count($err)) JSONResponse::Error(implode("<br />", $err));
  
  $login->password = hash('sha256',$pass);
  $login->login();
  JSONResponse::Success();
}
