<?php
namespace SSBIN;
use \Trust\DB;
class Genus extends \Trust\Model{
  protected static $key_name="genus";
  protected static $table_name = "genus", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
