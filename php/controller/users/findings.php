<?php
if (!$login->validated) {include "$ctrlDir/403.php"; die();}
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
  'upload_spreadsheet','export_ss','savepic','delPic','picReorder',
  'getFamilies','getGenuses','getSpecies','getGrids',
  'saveNew','saveOld','delete','get'
];

const EXCEL_HEADERS = ['ID','Class','Picture','Local name','Other name','N'
    ,'Family','Genus','Species','Common name','Survey-Month','Survey-Years','Latitude','Longitude','Grid'
    ,'Location Village','Location District','Landcover','IUCN Status','CITES Status','Indonesia Status'
    ,'Data Source','Reference','Other information'];

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
  more_pager($more,$p);

  $p->findings = Finding::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Finding::countWhere($p->strWhere, []);

  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  Finding::validateInputObject($o);
  Finding::prepareInputForDB($o);
  Finding::createValidationInfo($o);

  $o = new Finding($o); $o->pic=[];
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() { global $login;
  $o = Forms::getPostObject('o');
  $targetid = Forms::getPostObject('target');
  if (!is_numeric($targetid)) JSONResponse::Error('Invalid id');
  Finding::validateInputObject($o);
  Finding::prepareInputForDB($o);

  $old = Finding::find($targetid);
  if (!$old) JSONResponse::Error("Record not found");
  if ($old->data_info->created_by != $login->username) JSONResponse::Error("Can not delete other people's data");
  if ($old->validation->validated) JSONResponse::Error('Data already validated. Can not update from here.');

  unset ($o->validation, $o->pic);
  foreach ($o as $k=>$v) $old->$k = $v;
  $old->update();

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$o]);
}

function delete() { global $login;
  $targetid = Forms::getPostObject('o');
  $f = Finding::find($targetid);
  if ($f->data_info->created_by != $login->username) JSONResponse::Error("Can not delete other people's data");
  if ($f->validation->validated) JSONResponse::Error('Data already validated. Can not delete from here.');
  Finding::delete($targetid); //pics also get deleted here.
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}

function upload_spreadsheet() { global $login;
  $upload = $_FILES['spreadsheet'];
  $err = Excel::checkUpload($upload);
  if ($err) JSONResponse::Error($err);

  $filename = "uploads/u-$login->id-$upload[name]";
  move_uploaded_file($upload['tmp_name'], $filename);
  
  $oExcel = Excel::getExcelObject($filename);
  $starttime = microtime(true);
  $sheet = $oExcel->getSheetByName("Findings");
  if ($sheet ==  null) JSONResponse::Error('Worksheet "Findings" not found');

  $errors = []; $findings = [];
  for ($i=0; $i<23; $i++) {
    $head = $sheet->getCellByColumnAndRow($i,1)->getValue();
    if ($head != EXCEL_HEADERS[$i]) $errors[] = "Column #$i:'$head' should be '".EXCEL_HEADERS[$i]."'";
  }
  if (count($errors)) JSONResponse::Error('Column header mismatch. Press F12 For more information',['data'=>$errors]);
  
  //Note: pic column will be ignored
  $di = json_encode(\Trust\Model::newDataInfo());
  $jumbar = $sheet->getHighestRow();
  for ($i=2;$i<=$jumbar; $i++) {
    $month = Date::monthFromName($sheet->getCellByColumnAndRow(10,$i)->getValue());
    if (!$month) { $errors[]="Row #$i:Unrecognized month name"; continue; }
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
      'survey_month'=> $month,
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
    $o = new Finding($f); $o->pic=[];
    $check = Finding::validationString($o);
    if ($check!=null) $errors[]="Row #$i:$check";
    Finding::createValidationInfo($o);
    Finding::prepareInputForDB($o);
    $o->json_encode();
    $findings[] = $o;
  }
  if (count($errors)) JSONResponse::Error('Data validation error. Press F12 for more information',['data'=>$errors]);
  
  if (!count($findings)) JSONResponse::Error('No rows found');
  Finding::multiInsert($findings);
  \Trust\DB::exec("SELECT setval('findings_id_seq', (SELECT MAX(id) FROM findings));", []);
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
function export_ss() {
  $_POST['pager'] = json_decode($_POST['pager']);
  $_POST['more'] = json_decode($_POST['more']);
  $p = Pager::GetQueryAttributes();
  $more = Forms::getPostObject('more');
  more_pager($more, $p);
  
  $findings = Finding::allWhere("$p->strWhere $p->strOrder", []);
  //prepare for export
  $count = count($findings);
  $idx=0;$rows=[];
  while ($idx<$count) { $idx++;
    $o = array_shift($findings); //biar hemat memori
    $date_stamp = strtotime($o->survey_date);
    $month = date('F',$date_stamp);
    $year = date('Y',$date_stamp);
    $rows[]=[
      $o->id,
      $o->taxonomy->class,
      '',//pic
      $o->localname,
      $o->othername,
      $o->n,
      $o->taxonomy->family,
      $o->taxonomy->genus,
      $o->taxonomy->species,
      $o->commonname,
      $month,
      $year,
      $o->latitude,
      $o->longitude,
      $o->grid,
      $o->village,
      $o->district,
      $o->landcover,
      $o->iucn_status,
      $o->cites_status,
      $o->indo_status,
      $o->data_source,
      $o->reference,
      $o->other_info
    ];
  }
  
  require DIR.'/phplib/excel/PHPExcel.php';
  $title = 'Findings';
  $oExcel = new PHPExcel();
  $oExcel->getProperties()
    ->setCreator(APPNAME)
    ->setLastModifiedBy(APPNAME)
    ->setTitle($title)
    ->setSubject($title)
    ->setDescription("");
  
  $req_index = [0,1,2,3,4,5];
  $sheet = $oExcel->setActiveSheetIndex(0);
  $sheet->setTitle('Findings');
  
  $styleArray = array('font'=> array('bold'=>true,'color'=>array('rgb'=>'FF0000')));
  foreach ($req_index as $colIdx) $sheet->getStyleByColumnAndRow($colIdx)->applyFromArray($styleArray);
  
  $colCount=count(EXCEL_HEADERS);
  for($i=0; $i<$colCount; $i++) $sheet->setCellValueByColumnAndRow($i, 1, EXCEL_HEADERS[$i]);

  $sheet->fromArray($rows, null,'A2');
  for ($i=0; $i<=$colCount; $i++) $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="Data.xlsx"');
  header('Cache-Control: max-age=0');

  $objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'Excel2007');
  //$objWriter = PHPExcel_IOFactory::createWriter($oExcel, 'PDF');
  $objWriter->save('php://output');
}

function more_pager($more, &$p) {
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
}