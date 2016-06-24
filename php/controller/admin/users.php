<?php
if (!count($_POST)) { include DIR."/php/view/admin/users.php"; die(); }

use Trust\JSONResponse;
use SSBIN\User;
use Trust\Forms;
$services = ['toggleValidation','save','saveExpertise'];
if (in_array($_POST['a'], $services)) $_POST['a']();

function toggleValidation() { global $login;
  if (!isset($_POST['id']) || !is_numeric($_POST['id'])) JSONResponse::Error("User id error");
  $id = $_POST['id'];
  if ($login->id == $id) JSONResponse::Error("Cannot validate self");
  $user = User::find($id,"id,validated,data_info,active");
  if (!$user->active) JSONResponse::Error("User need to activate his/her account first");
  $user->validated = !$user->validated;
  $user->save();
  JSONResponse::Success(["validated"=>$user->validated]);
}

function save() { global $login;
  if (!isset($_POST['u'])) JSONResponse::Error("Error sending user data");
  $u = json_decode(json_encode($_POST['u']));
  if ($u->id == $login->id && $u->level != $login->level) JSONResponse::Error ("Can't alter self's level.");
  $user = User::find($u->id);
  if (!$user->active) JSONResponse::Error("User has not been activated yet.");
  if ($u->id == 1 && $u->level != $user->level) JSONResponse::Error ("Can't alter first user's level");
  $user->level = $u->level;
  $user->category = $u->category;
  $user->organization = $u->organization;
  $user->save();
  
  JSONResponse::Success(['message'=>'Saved successfully','o'=>$user]);
}

function saveExpertise() {
  $u = Forms::getPostObject('u');
  $user = User::find($u->id);
  if (!$user->active) JSONResponse::Error("User has not been activated yet");
  $user->expertise = $u->expertise;
  $user->save();
  
  JSONResponse::Success(['message'=>'Expertise updated successfully','o'=>$user]);
}
