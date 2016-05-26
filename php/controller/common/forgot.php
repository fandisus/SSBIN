<?php
if (!count($_POST) && !isset($_GET['k'])) { include DIR."/php/view/common/forgot.php"; die(); }

use Trust\JSONResponse;
use Trust\Basic;
use SSBIN\User;

$services = ['password','email'];
if (isset($_POST['a']) && in_array($_POST['a'],$services)) $_POST['a']();

if (isset($_GET['k'])) {
  $viewMode = "";
  $p = User::findByForgotToken($_GET['k']);
  if ($p == null) $viewMode = "TokenNotFound"; //or expired
  else {
    $viewMode = "ResetPass"; //NOTE: Ndak perlu unset forgot_token dan forgot_expiry, ntar jugo expired dewek
    unset($p->password);
    $_SESSION['login'] = $p;
    $login = $p;
  }
  include DIR."/php/view/common/forgot.php"; die();
}

function email() {
  $email = $_POST['email'];
  if (!Basic::validateEmail($email)) JSONResponse::Error ("Invalid email");
  $p = User::findByEmailOrUsername($email);
  if ($p == null) JSONResponse::Error("Email not registered");
  
  $p->sendForgotEmail();
  JSONResponse::Success(["msg"=>"An email with instructions to reset password has been sent to $email.<br />Please go to your email inbox and follow the instructions."]);
}

function password() { global $login;
  $pass = $_POST['pass'];
  $repass = $_POST['repass'];
  if ($pass != $repass) JSONResponse::Error("Password confirmation is incorrect");
  $err = Basic::validatePass($pass);
  if (count($err)) JSONResponse::Error(implode("<br />", $err));
  
  $login->password = hash('sha256',$pass);
  $login->login();
  JSONResponse::Success(["msg"=>"The password has been successfully changed. You will be redirected to home in 5 seconds"]);
}
