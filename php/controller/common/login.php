<?php
if (isset($login)) header("location:/"); //redirect bila sudah login
if (!count($_POST)) { include DIR."/php/view/common/login.php"; die(); }

use Trust\Server;
use Trust\JSONResponse;
use SSBIN\User;

$services = ["login"];
if (isset($_POST['a']) && in_array($_POST['a'], $services)) $_POST['a']();

function login() {
  $user = json_decode(json_encode($_POST['user'])); //login, pass, remember
  if (trim($user->login) == '') JSONResponse::Error ("Please fill the username");
  $p = User::findByEmailOrUsername($user->login);
  if ($p == null) JSONResponse::Error("Username or email not found.");
  if (hash('sha256',$user->pass) != $p->password) JSONResponse::Error("Wrong password.");
  
  unset ($p->password);
  $_SESSION['login']=$p;
  if ($user->remember == 'true') $p->login(time()+84600*14);
  else $p->login();
  JSONResponse::Success();
}
