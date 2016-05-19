<?php
namespace Trust;
class JSONResponse {
  static function Success($arr=[]) {
    $arr["result"]="success";
    echo json_encode($arr);
    die();
  }
  static function Error($msg,$arr=[]) {
    $arr["result"]="error";
    $arr['message']=$msg;
    echo json_encode($arr);
    die();
  }
  static function Debug($data) {
    echo json_encode(["result"=>"debug", "data"=>$data]);
    die();
  }  
}
