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
}
