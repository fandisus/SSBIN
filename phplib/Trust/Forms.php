<?php
namespace Trust;
class Forms {
  public static function getPostObject($name) {
    if (!isset($_POST[$name])) JSONResponse::Error("Object $name not found");
    $o = json_decode(json_encode($_POST[$name]));
    if ($o == null) JSONResponse::Error("Failed to post data");
    return $o;
  }
}
