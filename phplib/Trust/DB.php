<?php
namespace Trust;
class DB {
  private static $initialized = false;
  public static $pdo;
  protected static $db = DBNAME, $user = DBUSER, $pass = DBPASS, $port = DBPORT;
  public static function setConnection($db, $user, $pass, $port) {
    list(static::$db,static::$user,static::$pass,static::$port) = [$db,$user,$pass,$port];
  }
  public static function init($force = true) {
    if (!$force && static::$initialized) return;
    $host = (PHP_OS == "Linux") ? "localhost" : "127.0.0.1";
    $pdo = new \PDO(
            "pgsql:host=$host;port=".static::$port.";dbname=".static::$db.";", static::$user, static::$pass, array(
              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
            ));
    DB::$pdo = $pdo;
    static::$initialized = true;
  }
  public static function exec($sql, $bindings) {
    static::init();
    try {
      $sth = static::$pdo->prepare($sql);
      foreach ($bindings as $k=>$v) $sth->bindParam($k,$bindings[$k]);
      $sth->execute();
      return $sth->rowCount();
    } catch (\Exception $ex) {
      throw $ex;
    }
  }
  //Todo: tambah method update, insertMulti dan updateMulti
  public static function insert($sql, $bindings, $sequenceName=FALSE) {
    static::init();
    try {
      $sth = static::$pdo->prepare($sql);
      foreach ($bindings as $k=>$v) $sth->bindParam($k,$bindings[$k]);
      $sth->execute();
      if ($sequenceName) return static::$pdo->lastInsertId($sequenceName);
      return true;
    } catch (\Exception $ex) {
      throw $ex;
    }
  }
  public static function getOneVal($sql,$bindings=[]) {
    static::init();
    try {
      $sth = static::$pdo->prepare($sql);
      foreach ($bindings as $k=>$v) $sth->bindParam($k,$bindings[$k]);
      $sth->execute();
      $baris = $sth->fetch(\PDO::FETCH_NUM);
      if (!$baris) return null;
      return $baris[0];
    } catch (\Exception $ex) {
      throw $ex;
    }
  }
  public static function rowExists($sql,$bindings=[]) {
    static::init();
    try {
      $sth = static::$pdo->prepare($sql);
      foreach ($bindings as $k=>$v) $sth->bindParam($k,$bindings[$k]);
      $sth->execute();
      $baris = $sth->fetch(\PDO::FETCH_NUM);
      if (!$baris) return false;
      return true;
    } catch (\Exception $ex) {
      throw $ex;
    }
  }
  public static function get($sql, $bindings) {
    static::init();
    try {
      $sth = static::$pdo->prepare($sql);
      foreach ($bindings as $k=>$v) $sth->bindParam($k,$bindings[$k]);
      $sth->execute();
      return $sth->fetchAll();
    } catch (\Exception $ex) {
      throw $ex;
    }
  }
  public static function pgBackup($dbname, $backupInfo) {
    static::$db = $dbname;
    try { static::init(true); }
    catch (\Exception $ex) { throw new \Exception('Failed to connect to database',0,$ex); }

    putenv('PGPASSWORD='.DB::$pass);
    exec('pg_dump -U '.DB::$user.' -p '.DB::$port.' -d '.$dbname.' -c -O',$out, $ret);//pg_dump -U '.DB::$user.' -p '.DB::$port.' -d '.$dbname.' -c -O
    putenv('PGPASSWORD');
    if (!count($out)) die ('Database backup failed');
    $filesize = 0;
    array_unshift($out, $backupInfo);
    foreach ($out as $v) $filesize += strlen($v);
    $filesize += (count($out)) * strlen(PHP_EOL);
    $out = gzencode(implode(PHP_EOL, $out),5);
    

    header("Content-Disposition: attachment; filename=\"".date('Ymd').".ssbin\"");
    header("Content-type: application/octet-stream");
    header("Content-Length: " .$filesize);
    header("Connection: close");
    
    //foreach ($out as $v) echo $v.PHP_EOL;
    echo $out;
  }
}
