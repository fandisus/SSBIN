<?php
if (!count($_POST)) {
  if (!isset($paths[2]) || !in_array($paths[2],['backup','restore'])) { include "$ctrlDir/404.php"; die(); }
  include DIR."/php/view/admin/$paths[2].php";
  die();
}

use Trust\JSONResponse;
use Trust\DB;
use Trust\Files;

$services = ['backup','restore'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function backup() {
  $backupInfo = json_encode([
    "ver"=>1, "rev"=>0, "app"=>APPNAME,
    "created_at"=>date("y-M-d"),
    "created_by"=>$_SESSION['login']->username
  ]);

  try { DB::pgBackup(DBNAME, $backupInfo); }
  catch (Exception $ex) { die($ex->getMessage()); }
}

function restore() {
  Files::checkUpload($_FILES['ssbin']);
  try { DB::pgRestore(DBNAME, $_FILES['ssbin']); }
  catch (Exception $ex) { die($ex->getMessage()); }
  
  JSONResponse::Success(['message'=>'Database restored successfully']);
}