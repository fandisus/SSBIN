<?php
namespace SSBIN;
use \Trust\DB;
class Grid extends \Trust\Model{
  protected static $key_name="grid";
  protected static $table_name = "grids", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
  public static function getList() {
    $all = static::allWhere('ORDER BY grid ASC',[]);
    foreach ($all as $k=>$v) $all[$k] = $v->grid;
    return $all;
  }
}
