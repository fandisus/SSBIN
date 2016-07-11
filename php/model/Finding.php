<?php
namespace SSBIN;
use Trust\JSONResponse;
use Trust\Geo;
use SSBIN\Classes;
use SSBIN\IUCN_status; use SSBIN\Indo_status; use SSBIN\Location; use SSBIN\Landcover; use SSBIN\Grid;

class Finding extends \Trust\Model{
  protected static $key_name="id";
  protected static $table_name = "findings", $increment=true, $hasTimestamps = true;
  protected static $json_columns = ['pic','taxonomy','data_info','validation'];
  protected static $lookups=null;
  public static function validateInputObject($o) {
    $strError = static::validationString($o);
    if ($strError) JSONResponse::Error($strError);
  }
  public static function validationString($o) {
    if (Finding::$lookups == null) Finding::populateLookup();
    if ($o->taxonomy->class == '') return "Class cannot be empty";
    if (!in_array($o->taxonomy->class,  Finding::$lookups['classes'])) return 'Class not known. Please choose only from existing classes';
    if ($o->localname == '') return 'Local name cannot be empty';
    if ($o->othername == '') return 'Other name cannot be empty';
    if ($o->n == '' || !is_numeric($o->n) || $o->n < 1) return 'N cannot be empty';
    if ($o->survey_month == '' || !is_numeric($o->survey_month)) return 'Survey month cannot be empty';
    if ($o->survey_year == '' || !is_numeric($o->survey_year)) return 'Survey year cannot be empty';
    if ($o->latitude != '' && Geo::degreeFromStr($o->latitude,'lat') == null) return 'Invalid latitude';
    if ($o->longitude != '' && Geo::degreeFromStr($o->longitude,'long') == null) return 'Invalid longitude';
    if ($o->grid != '' && !in_array($o->grid, Finding::$lookups['grids'])) return 'Grid not listed';
    if ($o->district != '' && !in_array($o->district, Finding::$lookups['districts'])) return 'Location not listed';
    if ($o->landcover != '' && !in_array($o->landcover, Finding::$lookups['landcovers'])) return 'Landcover not listed';
    if ($o->iucn_status != '' && !in_array($o->iucn_status, Finding::$lookups['iucns'])) return 'IUCN Status not listed';
    if ($o->indo_status != '' && !in_array($o->indo_status, Finding::$lookups['indos'])) return 'Indonesia status not listed';
    return null;
  }
  public static function populateLookup() {
    $lookups = [
      'classes'=>  Classes::getList(),
      'grids'=>  Grid::getList(),
      'districts'=>  Location::getList(),
      'landcovers'=> Landcover::getList(),
      'iucns'=>  IUCN_status::getList(),
      'indos'=> Indo_status::getList()
    ];
    Finding::$lookups = $lookups;
  }
  public static function prepareInputForDB(&$o) {
    $o->survey_date = "$o->survey_year-$o->survey_month-01";
    unset ($o->survey_month, $o->survey_year);
    $o->date_precision = 'month';
    $o->latitude = ($o->latitude == '') ? null : Geo::degreeFromStr($o->latitude,'lat');
    $o->longitude = ($o->longitude == '') ? null : Geo::degreeFromStr($o->longitude,'long');
    $o->grid = ($o->grid == '') ? null : intval($o->grid);
  }
  public static function createValidationInfo(&$o) {
    $o->validation = ['validated'=>false,'validated_by'=>null,'validated_at'=>null];
  }
}
