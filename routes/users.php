<?php
if (!isset($login)) header("location:/");
$ctrlDir = DIR."/php/controller";
$template = DIR."/php/view/tulang.php";
$services = ["password","profile"];

if (!isset($paths[1]) || $paths[1]=="") include "$ctrlDir/users/home.php";
elseif (in_array($paths[1],$services)) {
  if (!$login->active) header("location:/users");
  include "$ctrlDir/users/$paths[1].php";
}
else { include "$ctrlDir/404.php"; die(); }
