<?php
namespace Trust;
class Basic {
  public static function Rp($money=null) {
    if ($money == null) return "Rp.0";
    return "Rp.".number_format($money, 0, ",", ".");
  }
  public static function RpShort($money=null) {
    if ($money == null) return "Rp.0";
    $suffix = "";
    if ($money >= 1000000) { $money = round($money/1000000, 2); $suffix = "M"; }
    elseif ($money >= 1000) { $money = round($money/1000, 0); $suffix = "K"; }
    return "Rp.".number_format($money, 0, ",", ".").$suffix;
  }
  public static function array_merge() { //Merges $arrParam1, $arrParam2, ...
    $args = func_get_args(); //assume all args are arrays
    $all = [];
    foreach ($args as $v) foreach ($v as $i) $all[] = $i;
    return $all;
  }
  public static function array_merge_inside($arr) { //Merges $arr[0]~array, $arr[1]~array, ...
    $all = [];
    foreach ($arr as $v) foreach ($v as $i) $all[] = $i;
    return $all;
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
  
  function RandomString($num) { 
    $characters = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';//tanpa huruf kecil dan O
    $randstring = '';
    for ($i = 0; $i < $num; $i++) {
      $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
  }

}
