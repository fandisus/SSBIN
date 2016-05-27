<?php
if (!count($_POST)) { include DIR."/php/view/users/profile.php"; die(); }

$services = ['biodata','nullPP','PP'];
if (isset($_POST['a']) && in_array($_POST['a'], $services)) $_POST['a']();
//TODO: Menu untuk Link to facebook jika ndak "register with facebook"
//TODO: Bikin profile settings. Untuk setting apa saja yang visible di public
use Trust\JSONResponse;
use Trust\Image;
use Trust\Date;
function kontak() { global $login;
  $kontak = json_decode(json_encode($_POST['kontak']));
  if ($login->kontak->fbid != null) $kontak->fbid = $login->kontak->fbid;
  
  foreach ($kontak as $k=>$v) if (!is_array($v) && trim($v) == '') $kontak->$k = null;
  if (!isset($kontak->telepon)) $kontak->telepon = [];
  foreach ($kontak->telepon as $k=>$v) if (trim($v) == '') unset($kontak->telepon[$k]);
  
  if ($kontak->nama == null) JSONResponse::Error("Nama harus diisi"); //note: di atas sudah dibikin null
  $login->kontak = $kontak;
  $_SESSION['login'] = $login;
  $login->save();
  
  JSONResponse::Success(['kontak'=>$kontak]);
}

function biodata() { global $login;
  if (!isset($_POST['biodata'])) JSONResponse::Error("Error sending data");
  $bio = json_decode(json_encode($_POST['biodata']));
  
  
  foreach ($bio as $k=>$v) if (!is_array($v) && trim($v) == '') $bio->$k = null;
  if (!isset($bio->phone)) $bio->phone = [];
  foreach ($bio->phone as $k=>$v) if (trim($v) == '') unset($bio->phone[$k]);

  if ($bio->gender == null) JSONResponse::Error("Please input gender");
  if ($bio->category == null) JSONResponse::Error ("Please input category");
  if ($bio->organization == null) JSONResponse::Error("Please input organization");
  if ($bio->dob != null && !Date::isJavaDate($bio->dob)) JSONResponse::Error("Invalid DOB");

  $login->category = $bio->category;
  $login->organization = $bio->organization;
  $login->biodata = json_decode(json_encode($bio));
  unset ($login->biodata->category, $login->biodata->organization);
  $login->biodata->dob = ($bio->dob == null) ? null : Date::fromJavascriptToSQLDate($bio->dob);
  $login->save();
  
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
