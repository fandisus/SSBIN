<?php
$ctrlDir = DIR."/php/controller";
if (!isset($login) || $login->level != \SSBIN\User::USER_ADMIN) { include "$ctrlDir/403.php"; die(); }
$template = DIR."/php/view/admin/tulang.php";
$services = [
    "users", //admins, experts, standard, validate,
    "organizations", //categories, organizations
    "taxonomies", //kingdom ---> species
    "database", //backup, restore, export
    "visitors"
    ];
$taxonServices = ['classes','families','genus','species'];

if (!isset($paths[1]) || $paths[1]=="") include "$ctrlDir/admin/home.php";
elseif ($paths[1]=='taxonomies' && in_array($paths[2], $taxonServices)) 
  { include "$ctrlDir/admin/$paths[2].php"; die(); }
elseif (in_array($paths[1], $services)) include "$ctrlDir/admin/$paths[1].php";
else { include "$ctrlDir/404.php"; die(); }
