<?php
include "../phplib/autoload.php";
session_start();
if ($_SERVER['REQUEST_URI'] == '/index.php') header('location:/');
//CSRF Token validation
if (count($_POST) && !\Trust\Server::csrf_is_valid()) \Trust\JSONResponse::Error ("Invalid CSRF Token"); else unset($_POST['token']);

if (isset($_SESSION['login'])) {
  $login = $_SESSION['login'];
  $_ENV['USER'] = $login->username;
} elseif (isset($_COOKIE['login'])) {
  $p = \SSBIN\User::findByCookies();
  if ($p == null) $_ENV['USER'] = null;
  else {
    $p->login(time()+84600*14);
    $login = $_SESSION['login'] = $p;
    $_ENV['USER'] = $login->username;
  }
} else {
  $_ENV['USER'] = null;
}

$log = new \SSBIN\Logger([
    "ip_address"=>$_SERVER['REMOTE_ADDR'],
    "time"=>date("Y-m-d H:i:s"),
    "requested_url"=>$_SERVER['REQUEST_URI'],
    "userid"=>(isset($login)) ? $login->id : null,
    "post"=>(count($_POST)) ? $_POST : null
  ]);
$log->save();

//dari .htaccess: subdo dan path.
$paths = explode("/",$_GET['path']);
include "../routes/routes.php";
