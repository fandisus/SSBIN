<?php
namespace SSBIN;
use \Trust\DB;
class Logger extends \Trust\Model{
  protected static $table_name = "access_log", $increment=false, $hasTimestamps = false;
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
}
