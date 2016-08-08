<?php
if (isset($_GET['id'])) { include DIR.'/php/view/common/speciesshow.php'; die(); }
if (!count($_POST)) {include DIR.'/php/view/common/specieslist.php'; die(); }

use Trust\Pager;
use Trust\JSONResponse;
use Trust\Forms;
use Trust\Geo;
use SSBIN\Finding;

$services = ['get','showMap'];
if (!in_array($_POST['a'], $services)) JSONResponse::Error('Service not available');
$_POST['a']();

function showMap() { global $p, $message, $login;
  $GLOBALS['message'] = '';
  $p = (object) ['strWhere'=>'','strOrder'=>''];
  if (isset($_POST['more'])) $more = json_decode($_POST['more']); else $more = [];
  
  if ($more == null) $GLOBALS['message'] = 'Error reading map parameters';
  $GLOBALS['message'] = 'abc';
  more_pager($more,$p);

  $p->findings = Finding::allWhere($p->strWhere, [],  Finding::PUBLICFIELDS.',latitude,longitude');
  $p->totalItems = Finding::countWhere($p->strWhere, []);
  
  include DIR.'/php/view/common/speciesmap.php';
}
function get() {
  $p = Pager::GetQueryAttributes();
  if (isset($_POST['more'])) $more = Forms::getPostObject('more'); else $more = [];
  more_pager($more,$p);

  $p->findings = Finding::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Finding::countWhere($p->strWhere, []);

  JSONResponse::Success((array)$p);
}

function more_pager($more, &$p) {
  $wheres = [];
  $wheres = ["validation->>'validated'='true'"];
  //localname, othername, class, family, genus, species, commonname, surveydate, latitude, longitude,
  //grid, village, district, landcover, iucn_status, cites_status, indo_status, data_source, reference, other_info
  foreach ($more as $k=>$v) if (trim($v) == '') $more->$k = null;
  
  foreach ($more as $k=>$v) {
    $v = strtolower($v);
    if (in_array($k, ['startDate','endDate','startLong','endLong','startLat','endLat'])) continue;
    if (in_array($k, ['class','family','genus','species'])) $wheres[] = "LOWER(taxonomy->>'$k') LIKE '%$v%'";
    else $wheres[] = "LOWER($k) LIKE '%$v%'";
  }
  
  if (isset($more->startDate)) {
    if ($more->startDate == null ^ $more->endDate == null) JSONResponse::Error('Date range incomplete. Please input start and end survey date');
    if ($more->startDate != null) { //If date range not empty, validate.
      if ($more->startDate != null && !Date::isJavaDate($more->startDate)) JSONResponse::Error('Invalid date format at survey date start date');
      if ($more->endDate != null && !Date::isJavaDate($more->endDate)) JSONResponse::Error('Invalid date format at survey date end date');
      $startDate = Date::fromJavascriptToSQLDate($more->startDate);
      $endDate = Date::fromJavascriptToSQLDate($more->endDate);
      $wheres[] = "DATE_TRUNC('month',survey_date) BETWEEN '$startDate' AND '$endDate'";
    }
  }
  
  if (count($wheres)) {
    $p->strWhere = ($p->strWhere == '')
      ? 'WHERE '.implode(' AND ',$wheres) 
      : "$p->strWhere ".implode(' AND ',$wheres);
  }
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY id DESC";
}