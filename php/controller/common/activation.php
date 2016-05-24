<?php
if (!count($_POST)) { handleGet(); die(); }
if (count($_POST)) { handlePost(); die(); }


function handlePost() {
  foreach ($_POST as $k=>$v) $$k = $v;
  if ($a != 'resend') Trust\JSONResponse::Error("Invalid command");
  if (!isset($kode)) Trust\JSONResponse::Error ("Code not sent");
  $p = \SSBIN\User::findByActivationCode($kode);
  if ($p == null) Trust\JSONResponse::Error ("User not found");
  
  $p->login_info->activation_code = hash('haval192,5',time());
  $p->login_info->code_expiry = (time()+$p::$lama_berlaku*84600);
  unset($p->password, $p->data_info);
  $p->save();
  $p->sendActivationEmail();
  Trust\JSONResponse::Success(["message"=>"Code resent. Please check your email"]);
}

function handleGet() { global $viewMode, $login, $p;
  $viewMode = '';
  if (isset($_GET['c'])) {
    $p = \SSBIN\User::findByActivationCode($_GET['c']);
    if ($p == null) $viewMode = 'code_not_found';
    elseif ($p->login_info->code_expiry < time()) $viewMode = "code_has_expired";
    else {
      $viewMode = 'successful_activation';
      $p->active = 't';
      unset ($p->password);
      $p->login();
      $login = $_SESSION['login'];
    }
  }
  include DIR.'/php/view/common/activation.php'; die();
}
