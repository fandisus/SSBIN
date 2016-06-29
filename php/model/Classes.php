<?php
namespace SSBIN;
use \Trust\DB;
class Classes extends \Trust\Model{
  protected static $key_name="class";
  protected static $table_name = "classes", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
  public static function getList() {
    $all = static::allWhere('ORDER BY class ASC',[]);
    foreach ($all as $k=>$v) $all[$k] = $v->class;
    return $all;
  }
}
