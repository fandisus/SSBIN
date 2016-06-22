<?php
namespace SSBIN;
use \Trust\DB;
class Species extends \Trust\Model{
  protected static $key_name="species";
  protected static $table_name = "species", $increment=false, $hasTimestamps = true;
  protected static $json_columns = ['data_info'];
}
