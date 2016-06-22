<?php
namespace SSBIN;
use \Trust\DB;
class Families extends \Trust\Model{
  protected static $key_name="family";
  protected static $table_name = "families", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
