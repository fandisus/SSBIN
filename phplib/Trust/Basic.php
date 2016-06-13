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
  function RandomString($num) { 
    $characters = '0123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';//tanpa huruf kecil dan O
    $randstring = '';
    for ($i = 0; $i < $num; $i++) {
      $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
  }

}
