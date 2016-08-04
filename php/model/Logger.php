<?php
namespace SSBIN;
use \Trust\DB;
class Logger extends \Trust\Model{
  protected static $table_name = "access_logs", $increment=false, $hasTimestamps = false;
  protected static $json_columns = ['post'];
  public function insert() {
    $props = $this->publicPropsToArr();
    $cols = array_keys($props);
    $vals = array_values($props);

    $sql = "INSERT INTO \"".static::$table_name."\" (\"".implode("\",\"", $cols)."\") VALUES (:".implode(",:",$cols).")";
    try {
      DB::insert($sql, $props);
    } catch (\Exception $ex) {
      $props['userid'] = null; //mungkin butuh userid -1 biar biso bedain "belum login" dengan "error not found"
      try {
        DB::insert($sql, $props);
      } catch (\Exception $ex) {
        //failed to insert, do nothing
      }
    }
    return true;
  }
  
  public static function allWhere($strWhere, $colVals, $cols="*") {
    $sql = 'SELECT ip_address, time, requested_url, u.username AS userid, post '
  . 'FROM access_logs LEFT JOIN users u ON u.id=access_logs.userid '.$strWhere;
    try {
      $read = DB::get($sql, $colVals);
    } catch (\Exception $ex) {
      throw $ex;
    }
    foreach ($read as $k=>$v) $read[$k] = new static($v);
    return $read;
  }
}
