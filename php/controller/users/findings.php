<?php
if (!count($_POST)) { include DIR."/php/view/users/findings.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Families; use SSBIN\Genus; use SSBIN\Species; use SSBIN\Grid;
use SSBIN\Finding;
use Trust\Forms;
use Trust\Pager;

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

  $o = new Finding($o);
  $o->validation = $old->validation;
  $o->update();

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$o]);
}

function delete() {
  $targetid = Forms::getPostObject('o');
  Finding::delete($targetid);
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
