<?php
namespace SSBIN;
use \Trust\DB;
class Classes extends \Trust\Model{
  protected static $table_name = "classes", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
