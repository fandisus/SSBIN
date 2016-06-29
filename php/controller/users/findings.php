<?php
if (!count($_POST)) { include DIR."/php/view/users/findings.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Classes; use SSBIN\Families; use SSBIN\Genus; use SSBIN\Species;
use SSBIN\IUCN_status; use SSBIN\Indo_status; use SSBIN\Location; use SSBIN\Landcover; use SSBIN\Grid;
use SSBIN\Finding;
use Trust\Forms;
use Trust\Pager;

$services = ['getFamilies','getGenuses','getSpecies','getGrids','saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function getFamilies() {
  if (empty($_POST['q'])) JSONResponse::Debug('Family search is empty');
  $q = $_POST['q'];
  $res = Families::allWhere("WHERE lower(family) LIKE :search ORDER BY family ASC LIMIT 10", ['search'=>"$q%"],'family');
  foreach ($res as $k=>$v) $res[$k]=$v->family;
  JSONResponse::Success(['families'=>$res]);
}
function getGenuses() {
  if (empty($_POST['q'])) JSONResponse::Debug('Genus search is empty');
  $q = $_POST['q'];
  $res = Genus::allWhere("WHERE lower(genus) LIKE :search ORDER BY genus ASC LIMIT 10", ['search'=>"$q%"],'genus');
  foreach ($res as $k=>$v) $res[$k]=$v->genus;
  JSONResponse::Success(['genuses'=>$res]);
}
function getSpecies() {
  if (empty($_POST['q'])) JSONResponse::Debug('Species search is empty');
  $q = $_POST['q'];
  $res = Species::allWhere("WHERE lower(species) LIKE :search ORDER BY species ASC LIMIT 10", ['search'=>"$q%"],'species');
  foreach ($res as $k=>$v) $res[$k]=$v->species;
  JSONResponse::Success(['species'=>$res]);
}
function getGrids() {
  if (empty($_POST['q'])) JSONResponse::Debug('Grid search is empty');
  $q = $_POST['q'];
  $res = Grid::allWhere("WHERE lower(grid) LIKE :search ORDER BY grid ASC LIMIT 10", ['search'=>"$q%"],'grid');
  foreach ($res as $k=>$v) $res[$k]=$v->grid;
  JSONResponse::Success(['grids'=>$res]);
}

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY location ASC";
  $p->location = Location::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Location::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->location) == '') JSONResponse::Error("Location cannot be empty");
  $old = Location::find($o->location);
  if ($old != null) JSONResponse::Error("The location $o->location already exists");

  $o = new Location($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->location == $target->location) JSONResponse::Error("Location unchanged");
  if (trim($o->location) == '') JSONResponse::Error("Location cannot be empty");
  
  $check = Location::find($o->location);
  if ($check != null) JSONResponse::Error("The location $o->location already exists");

  $update = Location::find($target->location);
  $update->location = $o->location;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE locations SET location=:location, data_info=:info WHERE location=:target',
    [
      'location'=>$o->location,
      'target'=>$target->location,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Location::delete($o->location);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
