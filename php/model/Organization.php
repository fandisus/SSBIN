<?php
namespace SSBIN;
use \Trust\DB;
class Organization extends \Trust\Model{
  static protected $json_columns = ['data_info'];
  protected static $table_name = "organizations", $increment=false, $hasTimestamps = true;
}
