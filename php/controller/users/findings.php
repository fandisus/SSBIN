<?php
if (!count($_POST)) { include DIR."/php/view/users/findings.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Families; use SSBIN\Genus; use SSBIN\Species; use SSBIN\Grid;
use SSBIN\Finding;
use Trust\Forms;
use Trust\Pager;
use Trust\Date;
use Trust\Geo;

$services = ['getFamilies','getGenuses','getSpecies','getGrids','saveNew','saveOld','delete','get'];
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
  Finding::delete($targetid);
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
