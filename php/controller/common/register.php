<?php
if (!count($_POST)) { include DIR."/php/view/common/register.php"; die(); }

use SSBIN\User;
use Trust\JSONResponse;
use Trust\Basic;
$services = ["register","checkUsername","checkEmail"];
if (in_array($_POST['a'], $services)) $_POST['a']();

function register() {
  $user = json_decode(json_encode($_POST['user']));
  //email, nama, username, pass, repass, gender
  if (!isset($user->email) || !Basic::validateEmail($user->email)) JSONResponse::Error("Invalid email");
  $p = User::findByEmailOrUsername($user->email,"id");
  if ($p != null) JSONResponse::Error("$user->email has already registered in the system.",["email_error"=>true]);
  $err = User::validateUsername($user->username);
  if (count($err)) JSONResponse::Error(implode("<br />",$err));
  $err = Basic::validatePass($user->pass);
  if (count($err)) JSONResponse::Error(implode("<br />",$err));
  if ($user->pass != $user->repass) JSONResponse::Error("Konfirmasi password tidak sama");
  if (strlen($user->name) < 3) JSONResponse::Error("Please fill in your name");
  if (!in_array($user->gender, ['Male','Female'])) JSONResponse::Error("Please input gender");
  if ($user->category == "") JSONResponse::Error("Please input your category");
  if ($user->category != "Individual" && $user->organization == "") JSONResponse::Error ("Please input your organization");
  
  $p = new User([
      "username"=>$user->username,
      "password"=>hash('sha256',$user->pass),
      "biodata"=>json_encode([
          "city"=>null,"state"=>null,
          "name"=>$user->name, "phone"=>[],"email"=>$user->email,
          "gender"=>$user->gender,"dob"=>null,'profile_pic'=>null
      ]),
      "category"=>$user->category,
      "organization"=>$user->organization
      ],true);
  $p->save();
  $p->sendActivationEmail();
  
  JSONResponse::Success(["message"=>"Registration Successfull.\nYou will be automatically redirected to the login page."]);
}
function checkUsername() {global $login;
  $err = User::validateUsername($_POST['user']);
  if (count($err)) JSONResponse::Error(implode("\n",$err));
  JSONResponse::Success();
}
function checkEmail() {
  if (!isset($_POST['email']) || !Basic::validateEmail($_POST['email'])) JSONResponse::Error("Invalid email");
  $p = User::findByEmailOrUsername($_POST['email'],"id");
  if ($p != null) JSONResponse::Error("$_POST[email] has already registered in the system.");
  JSONResponse::Success();
}
