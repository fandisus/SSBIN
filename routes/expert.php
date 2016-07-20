<?php
$ctrlDir = DIR."/php/controller";
if (!isset($login) || !in_array($login->level,[\SSBIN\User::USER_ADMIN,\SSBIN\User::USER_EXPERT]))
  { include "$ctrlDir/403.php"; die(); }
$template = DIR."/php/view/expert/tulang.php";
$services = [
    "users", //validate users
    "input", //Findings data validation
    ];
$taxonServices = ['classes','families','genus','species']; //taxonomies

if (!isset($paths[1]) || $paths[1]=="") include "$ctrlDir/expert/home.php";
elseif ($paths[1]=='taxonomies' && in_array($paths[2], $taxonServices)) 
  { include "$ctrlDir/expert/$paths[2].php"; die(); }
elseif (in_array($paths[1], $services)) include "$ctrlDir/expert/$paths[1].php";
else { include "$ctrlDir/404.php"; die(); }
