<?php
namespace SSBIN;
use \Trust\DB;
class Pages extends \Trust\Model{
  static protected $json_columns = ['data_info'];
  protected static $table_name = "pages", $hasTimestamps = true;
}
