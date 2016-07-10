<?php
namespace SSBIN;
use \Trust\DB;
class IUCN_status extends \Trust\Model{
  protected static $key_name="abbr";
  protected static $table_name = "iucn_status", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
  public static function getList() {
    $all = static::allWhere('ORDER BY abbr ASC',[]);
    foreach ($all as $k=>$v) $all[$k] = $v->abbr;
    return $all;
  }
}
