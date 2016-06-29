<?php
namespace SSBIN;
use \Trust\DB;
class Location extends \Trust\Model{
  protected static $key_name="location";
  protected static $table_name = "locations", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
  public static function getList() {
    $all = static::all();
    foreach ($all as $k=>$v) $all[$k] = $v->location;
    return $all;
  }
}
