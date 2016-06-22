<?php
namespace SSBIN;
use \Trust\DB;
class Landcover extends \Trust\Model{
  protected static $key_name="landcover";
  protected static $table_name = "landcovers", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
