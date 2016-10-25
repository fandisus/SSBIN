<?php
if (!count($_POST)) { include DIR."/php/view/common/indices.php"; die(); }

use Trust\Forms;
use Trust\DB;
use Trust\JSONResponse;

$services=['calc'];
if (isset($_POST['a']) && in_array($_POST['a'], $services)) $_POST['a']();
else JSONResponse::Error('Service not available');

function calc() {
  $params = (isset($_POST['params'])) ? Forms::getPostObject('params') : [];
  $p = build_query($params);
  
  $strFields = "SELECT taxonomy->>'family' AS family, taxonomy->>'genus' AS genus, taxonomy->>'species' AS species, "
    . "SUM(n) AS n FROM findings ";
  $rows = DB::get($strFields.$p->strWhere.$p->strGroup.$p->strOrder, []);
  if (!count($rows))    JSONResponse::Error("No data found");
  $summ = calcIndices($rows);
  
  JSONResponse::Success([
    'message'=>'Data calculation finished',
    'rows'=>$rows,
    'summ'=>$summ
  ]);
}


function build_query($more) {
  $wheres = [];
  $wheres = ["validation->>'validated'='true'"];
  //class, family, genus, surveydate, district, landcover
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
  
  $res = (object) [
    'strOrder'=>" ORDER BY taxonomy->>'family', taxonomy->>'genus', taxonomy->>'species'",
    'strWhere'=>'',
    'strGroup'=>" GROUP BY taxonomy->>'family', taxonomy->>'genus', taxonomy->>'species'"
  ];
  if (count($wheres)) $res->strWhere = 'WHERE '.implode(' AND ',$wheres);
  return $res;
}

function calcIndices(&$rows) {
  //pi, lnpi, pilnpi
  //S, sum, H, Hmax, E, simpson
  $summ = (object) [
    'S'=>0,
    'sum'=>0,
    'H'=>0,
    'Hmax'=>0,
    'E'=>0,
    'simpson'=>0
  ];
  foreach ($rows as $v) {
    $summ->S++;
    $summ->sum += $v->n;
  }
  foreach ($rows as $k=>$v) {
    $pi = $v->n / $summ->sum;
    $lnpi = log($pi);
    $pilnpi = $pi * $lnpi;
    $rows[$k]->pi = round($pi,3);
    $rows[$k]->lnpi = round($lnpi,3);
    $rows[$k]->pilnpi = round($pilnpi,3);
    
    $summ->H -= $pilnpi;
    $summ->simpson += $pi*$pi;
  }
  $summ->Hmax = round(log($summ->S),3);
  $summ->E = ($summ->S == 0) ? 0 : round($summ->H / $summ->Hmax, 3);
  $summ->H = round($summ->H, 3);
  $summ->simpson = round($summ->simpson,4);
  return $summ;
}