<?php
if (!isset($login) || $login->level != \SSBIN\User::USER_ADMIN) header("location:/");
$ctrlDir = DIR."/php/controller";
$template = DIR."/php/view/admin/tulang.php";
$services = [
    "users", //admins, experts, standard, validate,
    "organizations", //categories, organizations
    "taxonomies", //kingdom ---> species
    "database" //backup, restore, export
    ];
if (!isset($paths[1]) || $paths[1]=="") include "$ctrlDir/admin/home.php";
elseif (in_array($paths[1], $services)) include "$ctrlDir/admin/$paths[1].php";
else { include "$ctrlDir/404.php"; die(); }
