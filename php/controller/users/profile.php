<?php
if (!count($_POST)) { include DIR."/php/view/users/profile.php"; die(); }

$services = ['biodata','nullPP','PP'];
if (isset($_POST['a']) && in_array($_POST['a'], $services)) $_POST['a']();
//TODO: Menu untuk Link to facebook jika ndak "register with facebook"
//TODO: Bikin profile settings. Untuk setting apa saja yang visible di public
use Trust\JSONResponse;
use Trust\Image;
use Trust\Date;

function biodata() { global $login;
  //Note to self: Category dan Organization sengaja diset tidak bisa diubah oleh user.
  if (!isset($_POST['biodata'])) JSONResponse::Error("Error sending data");
  $bio = json_decode(json_encode($_POST['biodata']));
  
  foreach ($bio as $k=>$v) if (!is_array($v) && trim($v) == '') $bio->$k = null;
  if (!isset($bio->phone)) $bio->phone = [];
  foreach ($bio->phone as $k=>$v) if (trim($v) == '') unset($bio->phone[$k]);

  if ($bio->gender == null) JSONResponse::Error("Please input gender");
  if ($bio->dob != null && !Date::isJavaDate($bio->dob)) JSONResponse::Error("Invalid DOB");

  $login->biodata = json_decode(json_encode($bio));
  $login->biodata->dob = ($bio->dob == null) ? null : Date::fromJavascriptToSQLDate($bio->dob);
  $login->save();
  unset ($login->password);
  //$_SESSION['login']=$login; --> //Caknyo dah otomatis karena $login adalah ref dari $_SESSION['login']
  
  JSONResponse::Success(["biodata"=>$bio]);
}
function PP() { global $login;
  $error = Image::checkImageUpload($_FILES['profile_pic']);
  if ($error != null) JSONResponse::Error($error); 
  
  //copy ke folder
  $file = $_FILES['profile_pic'];
  $ext = pathinfo($file['name'])['extension'];
  $picpath = DIR."/public/images/userpic/pict$login->id.$ext";
  $icopath = DIR."/public/images/userpic/icon$login->id.$ext";
  Image::GenerateThumb($file['tmp_name'], 300, 300, $picpath);
  Image::GenerateThumb($file['tmp_name'], 30, 30, $icopath);
  unlink($file['tmp_name']);
  $login->biodata->profile_pic = "$login->id.$ext";
  $login->save();
  JSONResponse::Success(["img_profile"=>$login->profilePic()]);
}
function nullPP() { global $login;
  $login->biodata->profile_pic = null;
  $login->save();
  JSONResponse::Success(["img_profile"=>$login->profilePic()]);
}
