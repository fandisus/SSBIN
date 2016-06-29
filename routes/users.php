<?php
if (!isset($login)) { include "$ctrlDir/403.php"; die(); }

$ctrlDir = DIR.'/php/controller';
$template = DIR.'/php/view/tulang.php';
$services = ['password','profile','findings'];

if (!isset($paths[1]) || $paths[1]=='') include "$ctrlDir/users/home.php";
elseif (in_array($paths[1],$services)) include "$ctrlDir/users/$paths[1].php";
else { include "$ctrlDir/404.php"; die(); }
