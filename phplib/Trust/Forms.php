<?php
namespace Trust;
class Forms {
  public static function getPostObject($name) {
    if (!isset($_POST[$name])) JSONResponse::Error("Object $name not found");
    $o = json_decode(json_encode($_POST[$name]));
    if ($o == null) JSONResponse::Error("Failed to post data");
    return $o;
  }
  public static function validateEmail($email) {
    return filter_var($email,FILTER_VALIDATE_EMAIL);
  }
  public static function validatePass($pwd) {
    $errors = [];
    if (strlen($pwd) < 7) $errors[] = "Password is too short!";
    //if (!preg_match("#[0-9]+#", $pwd)) $errors[] = "Password must include at least one number!";
    if (!preg_match("#[a-zA-Z]+#", $pwd)) $errors[] = "Password must include at least one number!";
    if(!preg_match("#[A-Z]+#", $pwd) ) $error[] = "Password must include at least one capital letter!";

    return $errors;
  }
  public static function validateUsername($user) {
    $errors = [];
    if (strlen($user) < 3) $errors[] = "Username too short";
    if (strlen($user) > 100) $errors[] = "Username too long";
    if (!preg_match('/^\w{3,100}$/', $user)) $errors[] = "Username can only contain alphanumeric characters. No symbols allowed";
    if (!preg_match('/^[A-Za-z_]/', $user)) $errors[] = "Username can not start with numbers";
    return $errors;
  }
}
