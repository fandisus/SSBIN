<?php
namespace SSBIN;
use SSBIN\Classes;
use SSBIN\IUCN_status; use SSBIN\Indo_status; use SSBIN\Location; use SSBIN\Landcover; use SSBIN\Grid;

class Finding extends \Trust\Model{
  protected static $key_name="id";
  protected static $table_name = "findings", $increment=true, $hasTimestamps = true;
  protected static $json_columns = ['pic','taxonomy','data_info','validation'];
  public static function validateInputObject($o) {
    if ($o->taxonomy->class == '') JSONResponse::Error("Class cannot be empty");
    if (!Classes::find($o->taxonomy->class, 'class'))
      JSONResponse::Error('Class not known. Please choose only from existing classes');
    if ($o->localname == '') JSONResponse::Error('Local name cannot be empty');
    if ($o->othername == '') JSONResponse::Error('Other name cannot be empty');
    if ($o->n == '' || !is_numeric($o->n) || $o->n < 1) JSONResponse::Error('N cannot be empty');
    if ($o->survey_month == '' || !is_numeric($o->survey_month)) JSONResponse::Error('Survey month cannot be empty');
    if ($o->survey_year == '' || !is_numeric($o->survey_year)) JSONResponse::Error('Survey year cannot be empty');
    if ($o->latitude != '' && !is_numeric($o->latitude)) JSONResponse::Error('Invalid latitude');
    if ($o->longitude != '' && !is_numeric($o->longitude)) JSONResponse::Error('Invalid longitude');
    if ($o->grid != '' && !Grid::find($o->grid,'grid')) JSONResponse::Error('Grid not listed');
    if ($o->district != '' && !Location::find($o->district,'location')) JSONResponse::Error('Location not listed');
    if ($o->landcover != '' && !Landcover::find($o->landcover,'landcover')) JSONResponse::Error('Landcover not listed');
    if ($o->iucn_status != '' && !IUCN_status::find($o->iucn_status,'abbr')) JSONResponse::Error('IUCN Status not listed');
    if ($o->indo_status != '' && !Indo_status::find($o->indo_status,'abbr')) JSONResponse::Error('Indonesia status not listed');
  }
  public static function prepareInputForDB(&$o) {
    $o->survey_date = "$o->survey_year-$o->survey_month-01";
    unset ($o->survey_month, $o->survey_year);
    $o->date_precision = 'month';
    $o->latitude = ($o->latitude == '') ? null : doubleval($o->latitude);
    $o->longitude = ($o->longitude == '') ? null : doubleval($o->longitude);
    $o->grid = ($o->grid == '') ? null : intval($o->grid);
  }
  public static function createValidationInfo(&$o) {
    $o->validation = ['validated'=>false,'validated_by'=>null,'validated_at'=>null];
  }
}
