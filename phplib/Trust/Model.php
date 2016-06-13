<?php
namespace Trust;
use Trust\DB;

interface iSaveable {
  public function save();
  public static function delete($id);
}
interface iLoadable {
  static function find($id, $cols="*");
  static function all($cols="*");
}
abstract class Model implements iSaveable, iLoadable {
  protected $old_vals;
  protected static $json_columns = [], $table_name;
  protected static $key_name="id", $increment=true, $hasTimestamps=true;
  
  //Constructor are meant to read from database, so json fields are expected to be in json format.
  public function __construct($arrProps) {
    foreach ($arrProps as $k=>$v) {
      $this->$k = $v;
      $this->old_vals[$k] = $v;
    }
    foreach (static::$json_columns as $col) {
      if (!isset($this->$col)) continue;
      if (!is_object($col)) continue;
      $this->$col = json_decode($this->$col); 
    }
  }
  public function save() {
    if (!isset($this->id) || !$this->id) $this->insert(); else $this->update();
  }

  public function publicPropsToArr($json=true) {
    $props = get_object_vars($this);
    $classVars = get_class_vars(get_class($this));
    foreach ($classVars as $k=>$v) unset($props[$k]); //Throw away protected properties
    if ($json) foreach (static::$json_columns as $v) { if (isset($props[$v])) $props[$v] = json_encode($props[$v]);}
    foreach ($props as $k=>$v) if (gettype($v) == "boolean") $props[$k] = ($v) ? 'true' : '0';
    //if (get_class($this) == "SSBIN\\User") JSONResponse::Debug ($props);
    return $props;
  }
  public function setTimestamps($forceNew = false) {
    if (!static::$hasTimestamps) return;
    $now = date("Y-m-d H:i:s");
    if (!isset($this->data_info) || $forceNew) { //kalau baru
      $info = new \stdClass();
      $info->created_by = $info->updated_by = $_ENV['USER'];
      $info->created_at = $info->updated_at = $now;
      $this->data_info = $info;
    } else {
      $this->data_info->updated_by=$_ENV['USER'];
      $this->data_info->updated_at=$now;
    }
  }
  public function insert() {
    $this->setTimestamps();
    $props = $this->publicPropsToArr();
    unset ($props['id']); //kalo ado id, buang, biar jadi default (AI)
    $cols = array_keys($props);
    $vals = array_values($props);
    
    $sql = "INSERT INTO \"".static::$table_name."\" (\"".implode("\",\"", $cols)."\") VALUES (:".implode(",:",$cols).")";
    try {
      if (static::$increment) {
        $this->{static::$key_name} = DB::insert($sql, $props, static::$table_name."_".static::$key_name."_seq");
      } else {
        DB::insert($sql, $props);
      }
    } catch (\Exception $ex) {
      throw $ex;
    }
    return true;
  }

  public function getDirtyProps(&$props) {
    foreach ($this->old_vals as $k=>$v) {
      //Kalau update, pasti old_vals ada semua, props ada semua. ndak ada berarti ndak mau diupdate.
      if (isset($props[$k]) && $props[$k] == $v) unset($props[$k]);
    }
  }
  public function update() {
    $this->setTimestamps();
    $props = $this->publicPropsToArr();
    $this->getDirtyProps($props);
    $props[static::$key_name] = $this->{static::$key_name};
    foreach ($props as $k=>$v) { $cols[]="$k=:$k"; }
    $sql = "UPDATE \"".static::$table_name."\" SET ".implode(",",$cols)." WHERE ".static::$key_name."=:".static::$key_name;
    try {
      return DB::exec($sql, $props);
    } catch (\Exception $ex) {
      throw $ex;
    }
  }

  public static function delete($PK) {
    $sql = "DELETE FROM \"".static::$table_name."\" WHERE ".static::$key_name."=:".static::$key_name;
    try {
      return DB::exec($sql, [static::$key_name=>$PK]);
    } catch (\Exception $ex) {
      throw $ex;
    }
  }

  public static function all($cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\"";
    try {
      $read = DB::get($sql, []);
    } catch (\Exception $ex) {
      throw $ex;
    }
    foreach ($read as $k=>$v) $read[$k] = new static($v);
    return $read;
  }

  public static function find($PK, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" WHERE ".static::$key_name."=:".static::$key_name;
    try {
      $read = DB::get($sql, [static::$key_name=>$PK]);
    } catch (\Exception $ex) {
      throw $ex;
    }
    if (count($read)) return new static($read[0]);
    return null;
  }
  public static function where($strWhere, $colVals, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" $strWhere";
    try {
      $read = DB::get($sql, $colVals);
    } catch (\Exception $ex) {
      throw $ex;
    }
    if (count($read)) return new static($read[0]);
    return null;
  }
  public static function allWhere($strWhere, $colVals, $cols="*") {
    $sql = "SELECT $cols FROM \"".static::$table_name."\" $strWhere";
    try {
      $read = DB::get($sql, $colVals);
    } catch (\Exception $ex) {
      throw $ex;
    }
    foreach ($read as $k=>$v) $read[$k] = new static($v);
    return $read;
  }
}
