<?php
namespace SSBIN;
use \Trust\DB;
class Finding extends \Trust\Model{
  protected static $key_name="id";
  protected static $table_name = "findings", $increment=true, $hasTimestamps = true;
  protected static $json_columns = ['pic','taxonomy','data_info','validation'];
}
