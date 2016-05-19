<?php
namespace Trust;
class TableComposer {
  public $tableName;
  protected $lastCol;
  protected $columns=[];
  protected $constraints=[];
  protected $indexes=[];
  protected $comments=[];
  
  protected function returner($colName) {
    $this->lastCol = $colName;
    return $this;
  }
  public function __construct($tableName) { $this->tableName = $tableName; }
  public function increments($colName) {
    $this->columns[] = "$colName SERIAL";
    return $this->returner($colName);
  }
  public function bigIncrements($colName) {
    $this->columns[] = "$colName BIGSERIAL";
    return $this->returner($colName);
  }
  public function string($colName, $length) {
    $this->columns[] = "$colName CHARACTER VARYING($length)";
    return $this->returner($colName);
  }
  public function text($colName) {
    $this->columns[] = "$colName TEXT";
    return $this->returner($colName);
  }
  public function integer($colName) {
    $this->columns[] = "$colName INTEGER";
    return $this->returner($colName);
  }
  public function bigInteger($colName) {
    $this->columns[] = "$colName BIGINT";
    return $this->returner($colName);
  }
  public function double($colName) {
    $this->columns[] = "$colName DOUBLE PRECISION";
    return $this->returner($colName);
  }
  public function bool($colName) {
    $this->columns[] = "$colName BOOL";
    return $this->returner($colName);
  }
  public function timestamp($colName) {
    $this->columns[] = "$colName TIMESTAMP";
    return $this->returner($colName);
  }
  public function date($colName) {
    $this->columns[] = "$colName DATE";
    return $this->returner($colName);
  }
  public function jsonb($colName) {
    $this->columns[] = "$colName JSONB";
    return $this->returner($colName);
  }

  
  public function notNull() {
    $this->columns[count($this->columns)-1] .= " NOT NULL";
    return $this;
  }
  public function unique() {
    $col = $this->lastCol;
    $this->constraints[] = "CONSTRAINT uq_$this->tableName"."_$col UNIQUE ($col)";
    return $this;
  }
  public function index() {
    $col = $this->lastCol;
    $this->indexes[] = "CREATE INDEX idx_$col"."_$this->tableName ON $this->tableName USING BTREE ($col);";
    return $this;
  }
  public function ginPropIndex($props) {
    $col = $this->lastCol;
    if (!is_array($props)) $props = [$props];
    foreach ($props as $v) {
      $this->indexes[] = "CREATE INDEX idx_$v"."_$col"."_$this->tableName ON $this->tableName USING GIN (($col"."->'$v'));";
    }
    return $this;
  }
  public function ginIndex() {
    $col = $this->lastCol;
    $this->indexes[] = "CREATE INDEX idx_$col"."_$this->tableName ON $this->tableName USING GIN ($col);";
    return $this;
  }
  public function primary($cols="") {
    if ($cols == "") $cols = $this->lastCol;
    $strCols = (is_array($cols)) ? implode(",",$cols) : $cols;
    $this->constraints[] = "CONSTRAINT pk_$this->tableName PRIMARY KEY ($strCols)";
    return $this;
  }
  public function foreign($ref,$refcol,$onupdate = "",$ondelete = "") {
    $col = $this->lastCol;
    $onupdate = ($onupdate == "") ? "" : " ON UPDATE $onupdate";
    $ondelete = ($ondelete == "") ? "" : " ON DELETE $ondelete";
    $this->constraints[] = "CONSTRAINT fk_$col"."_$this->tableName FOREIGN KEY ($col) REFERENCES $ref ($refcol)$onupdate$ondelete";
    return $this;
  }
  public function multiForeign($cols,$ref,$refcols,$onupdate,$ondelete) {
    $onupdate = ($onupdate == "") ? "" : " ON UPDATE $onupdate";
    $ondelete = ($ondelete == "") ? "" : " ON DELETE $ondelete";
    $this->constraints[] = "CONSTRAINT fk_$ref"."_$this->tableName FOREIGN KEY ($cols) REFERENCES $ref ($refcols)$onupdate$ondelete";
    return $this;
  }
  public function comment() {
    $args = func_get_args();
    if (count($args) == 1) {
      $col = $this->lastCol;
      $c = $args[0];
    } else {
      $col = $args[0];
      $c = $args[1];
    }
    $c = str_replace("'", "''", $c);
    $this->comments[] = "COMMENT ON COLUMN $this->tableName.$col IS '$c';";
    return $this;
  }
  
  public function parse() {
    $insides = \Trust\Basic::array_merge($this->columns, $this->constraints);
    $strInsides = implode(",\n  ", $insides);
    $creator = "CREATE TABLE $this->tableName (\n  $strInsides\n);";
    return \Trust\Basic::array_merge( [$creator], $this->indexes, $this->comments );
  }
}
