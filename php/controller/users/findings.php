<?php
if (!count($_POST)) { include DIR."/php/view/users/findings.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Families; use SSBIN\Genus; use SSBIN\Species; use SSBIN\Grid;
use SSBIN\Finding;
use Trust\Forms;
use Trust\Pager;
use Trust\Date;
use Trust\Geo;
use Trust\Excel;
use Trust\Image;

$services = [
  'upload_spreadsheet','savepic','delPic','picReorder',
  'getFamilies','getGenuses','getSpecies','getGrids',
  'saveNew','saveOld','delete','get'
];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function getFamilies() {
  if (empty($_POST['q'])) JSONResponse::Debug('Family search is empty');
  $q = strtolower($_POST['q']);
  $res = Families::allWhere("WHERE lower(family) LIKE :search ORDER BY family ASC LIMIT 10", ['search'=>"$q%"],'family');
  foreach ($res as $k=>$v) $res[$k]=$v->family;
  JSONResponse::Success(['families'=>$res]);
}
function getGenuses() {
  if (empty($_POST['q'])) JSONResponse::Debug('Genus search is empty');
  $q = strtolower($_POST['q']);
  $res = Genus::allWhere("WHERE lower(genus) LIKE :search ORDER BY genus ASC LIMIT 10", ['search'=>"$q%"],'genus');
  foreach ($res as $k=>$v) $res[$k]=$v->genus;
  JSONResponse::Success(['genuses'=>$res]);
}
function getSpecies() {
  if (empty($_POST['q'])) JSONResponse::Debug('Species search is empty');
  $q = strtolower($_POST['q']);
  $res = Species::allWhere("WHERE lower(species) LIKE :search ORDER BY species ASC LIMIT 10", ['search'=>"$q%"],'species');
  foreach ($res as $k=>$v) $res[$k]=$v->species;
  JSONResponse::Success(['species'=>$res]);
}
function getGrids() {
  if (empty($_POST['q'])) JSONResponse::Debug('Grid search is empty');
  $q = strtolower($_POST['q']);
  $res = Grid::allWhere("WHERE lower(grid) LIKE :search ORDER BY grid ASC LIMIT 10", ['search'=>"$q%"],'grid');
  foreach ($res as $k=>$v) $res[$k]=$v->grid;
  JSONResponse::Success(['grids'=>$res]);
}

function get() {
  $p = Pager::GetQueryAttributes();
  $more = Forms::getPostObject('more');
  foreach ($more as $k=>$v) if (trim($v) == '') $more->$k = null;
  $wheres = [];
  if ($more->startDate == null ^ $more->endDate == null) JSONResponse::Error('Date range incomplete. Please input start and end survey date');
  if ($more->startDate != null) { //If date range not empty, validate.
    if ($more->startDate != null && !Date::isJavaDate($more->startDate)) JSONResponse::Error('Invalid date format at survey date start date');
    if ($more->endDate != null && !Date::isJavaDate($more->endDate)) JSONResponse::Error('Invalid date format at survey date end date');
    $startDate = Date::fromJavascriptToSQLDate($more->startDate);
    $endDate = Date::fromJavascriptToSQLDate($more->endDate);
    $wheres[] = "DATE_TRUNC('month',survey_date) BETWEEN '$startDate' AND '$endDate'";
  }

  if ($more->startLat == null ^ $more->endLat == null) JSONResponse::Error('Incomplete latitude range');
  if ($more->startLat != null) { //if lat range not empty, validate
    $startLat = Geo::degreeFromStr($more->startLat, 'lat');
    $endLat = Geo::degreeFromStr($more->endLat, 'lat');
    if ($more->startLat != null && !$startLat) JSONResponse::Error ('Invalid input at starting latitude');
    if ($more->endLat != null && !$endLat) JSONResponse::Error ('Invalid input at ending latitude');
    $wheres[] = "latitude BETWEEN $startLat AND $endLat";
  }
  
  if ($more->startLong == null ^ $more->endLong == null) JSONResponse::Error('Incomplete longitude range');
  if ($more->startLong != null) { //if long range not empty, validate
    $startLong = Geo::degreeFromStr($more->startLong, 'long');
    $endLong = Geo::degreeFromStr($more->endLong, 'long');
    if ($more->startLong != null && !$startLong) JSONResponse::Error ('Invalid input at starting longitude');
    if ($more->endLong != null && !$endLong) JSONResponse::Error ('Invalid input at ending longitude');
    $wheres[] = "longitude BETWEEN $startLong AND $endLong";
  }
  if (count($wheres)) {
    $p->strWhere = ($p->strWhere == '') 
      ? 'WHERE '.implode(' AND ',$wheres) 
      : "$p->strWhere ".implode(' AND ',$wheres);
  }

  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY id DESC";
  $p->findings = Finding::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Finding::countWhere($p->strWhere, []);

  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  Finding::validateInputObject($o);
  Finding::prepareInputForDB($o);
  Finding::createValidationInfo($o);

  $o = new Finding($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $targetid = Forms::getPostObject('target');
  if (!is_numeric($targetid)) JSONResponse::Error('Invalid id');
  Finding::validateInputObject($o);
  Finding::prepareInputForDB($o);

  $old = Finding::find($targetid);
  if (!$old) JSONResponse::Error("Record not found");

  unset ($o->validation);
  foreach ($o as $k=>$v) $old->$k = $v;
  $old->update();

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$o]);
}

function delete() {
  $targetid = Forms::getPostObject('o');
  Finding::delete($targetid); //pics also get deleted here.
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}

function upload_spreadsheet() { global $login;
  $upload = $_FILES['spreadsheet'];
  $err = Excel::checkUpload($upload);
  if ($err) JSONResponse::Error($err);

  $filename = "uploads/u-$login->id-$upload[name]";
  move_uploaded_file($upload['tmp_name'], $filename);
  
  $headers=['ID','Class','Picture','Local name','Other name','N'
    ,'Family','Genus','Species','Common name','Survey-Month','Survey-Years','Latitude','Longitude','Grid'
    ,'Location Village','Location District','Landcover','IUCN Status','CITES Status','Indonesia Status'
    ,'Data Source','Reference','Other information'];
  $oExcel = Excel::getExcelObject($filename);
  $starttime = microtime(true);
  $sheet = $oExcel->getSheetByName("Findings");
  if ($sheet ==  null) JSONResponse::Error('Worksheet "Findings" not found');

  $errors = [];
  for ($i=0; $i<23; $i++) {
    $head = $sheet->getCellByColumnAndRow($i,1)->getValue();
    if ($head != $headers[$i]) $errors[] = "Column #$i:'$head' should be '".$headers[$i]."'";
  }
  if (count($errors)) JSONResponse::Error('Column header mismatch. Press F12 For more information',['data'=>$errors]);
  
  //Note: pic column will be ignored
  $di = json_encode(\Trust\Model::newDataInfo());
  $jumbar = $sheet->getHighestRow();
  for ($i=2;$i<=$jumbar; $i++) {
    $f = [
      'id'=>$sheet->getCellByColumnAndRow(0,$i)->getValue(),
      'pic'=>[],
      'localname'=>$sheet->getCellByColumnAndRow(3,$i)->getValue(),
      'othername'=>$sheet->getCellByColumnAndRow(4,$i)->getValue(),
      'n'=>$sheet->getCellByColumnAndRow(5,$i)->getValue(),
      'taxonomy'=>(object)[
        'class'=>$sheet->getCellByColumnAndRow(1,$i)->getValue(),
        'family'=>$sheet->getCellByColumnAndRow(6,$i)->getValue(),
        'genus'=>$sheet->getCellByColumnAndRow(7,$i)->getValue(),
        'species'=>$sheet->getCellByColumnAndRow(8,$i)->getValue()
      ],
      'commonname'=>$sheet->getCellByColumnAndRow(9,$i)->getValue(),
      'survey_month'=> Date::monthFromName($sheet->getCellByColumnAndRow(10,$i)->getValue()),
      'survey_year'=>$sheet->getCellByColumnAndRow(11,$i)->getValue(),
      'latitude'=>$sheet->getCellByColumnAndRow(12,$i)->getValue(),
      'longitude'=>$sheet->getCellByColumnAndRow(13,$i)->getValue(),
      'grid'=>$sheet->getCellByColumnAndRow(14,$i)->getValue(),
      'village'=>$sheet->getCellByColumnAndRow(15,$i)->getValue(),
      'district'=>$sheet->getCellByColumnAndRow(16,$i)->getValue(),
      'landcover'=>$sheet->getCellByColumnAndRow(17,$i)->getValue(),
      'iucn_status'=>$sheet->getCellByColumnAndRow(18,$i)->getValue(),
      'cites_status'=>$sheet->getCellByColumnAndRow(19,$i)->getValue(),
      'indo_status'=>$sheet->getCellByColumnAndRow(20,$i)->getValue(),
      'data_source'=>$sheet->getCellByColumnAndRow(21,$i)->getValue(),
      'reference'=>$sheet->getCellByColumnAndRow(22,$i)->getValue(),
      'other_info'=>$sheet->getCellByColumnAndRow(23,$i)->getValue(),
      'data_info'=>$di,
    ];
    $o = new Finding($f);
    $check = Finding::validationString($o);
    if ($check!=null) $errors[]="Row #$i:$check";
    Finding::createValidationInfo($o);
    Finding::prepareInputForDB($o);
    $o->json_encode();
    $findings[] = $o;
  }
  if (count($errors)) JSONResponse::Error('Data validation error. Press F12 for more information',['data'=>$errors]);
  
  Finding::multiInsert($findings);
  
  $endtime = microtime(true);
  $duration = $endtime - $starttime;
  foreach ($findings as $o) $o->json_decode();
  JSONResponse::Success(['message'=>"Upload successful ($duration s)",'findings'=>$findings]);
}

function savepic() {
  $id = Forms::getPostObject('target');
  $upload = $_FILES['pic'];
  $err = Image::checkImageUpload($upload);
  if ($err) JSONResponse::Error($err);

  $o = Finding::find($id);
  if (!$o) JSONResponse::Error('Data not found');

  $ext = pathinfo($upload['name'])['extension'];
  $filename = "$id-".time().".$ext";

  //Save to database
  $o->pic[] = $filename;
  $o->update();

  $iconpath = DIR.'/public'.Finding::ICONPATH."$filename";
  $thumbpath = DIR.'/public'.Finding::THUMBPATH."$filename";
  $picpath = DIR.'/public'.Finding::PICPATH."$filename";

  Image::GenerateThumb($upload['tmp_name'], 40, 40, $iconpath);
  Image::GenerateThumb($upload['tmp_name'], 160, 160, $thumbpath);
  move_uploaded_file($upload['tmp_name'], $picpath);

  JSONResponse::Success(['pic'=>$filename,'message'=>'Image uploaded successfully']);
}
function delPic() {
  $target = Forms::getPostObject('target');
  $pic = Forms::getPostObject('p');
  $o = Finding::find($target);
  if (!$o) JSONResponse::Error('Data not found');
  
  $rem = \Trust\Basic::array_remove($o->pic, $pic);
  if (!$rem) JSONResponse::Error('Picture deletion failed');
  $o->update();
  
  unlink(DIR.'/public'.Finding::ICONPATH.$pic);
  unlink(DIR.'/public'.Finding::THUMBPATH.$pic);
  unlink(DIR.'/public'.Finding::PICPATH.$pic);
  
  JSONResponse::Success(['message'=>'Picture deleted']);
}
function picReorder() {
  $target = Forms::getPostObject('target');
  $pics = Forms::getPostObject('pics');
  
  $o = Finding::find($target);
  if (!$o) JSONResponse::Error('Data not found');
  
  $o->pic = $pics;
  $o->update();
  
  JSONResponse::Success(['message'=>'Picture reordered']);
}