<?php
namespace SSBIN;
use \Trust\DB;
class IUCN_status extends \Trust\Model{
  protected static $table_name = "iucn_status", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
