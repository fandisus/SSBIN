<?php
namespace SSBIN;
use \Trust\DB;
class Indo_status extends \Trust\Model{
  protected static $table_name = "indo_status", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
