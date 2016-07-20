<?php
$ctrlDir = DIR."/php/controller";
$services = ["login","logout","activation","register","forgot","specieslist","userslist","profile"];

if ($paths[0] == "") include "$ctrlDir/common/home.php";
elseif (in_array($paths[0], $services)) include "$ctrlDir/common/$paths[0].php";
else { include "$ctrlDir/404.php"; die(); }
