<?php
if (!count($_POST)) { include DIR."/php/view/admin/grid.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Grid;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY grid ASC";
  $p->grid = Grid::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Grid::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->grid) == '') JSONResponse::Error("Grid cannot be empty");
  $old = Grid::find($o->grid);
  if ($old != null) JSONResponse::Error("The grid $o->grid already exists");

  $o = new Grid($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->grid == $target->grid) JSONResponse::Error("Grid unchanged");
  if (trim($o->grid) == '') JSONResponse::Error("Grid cannot be empty");
  
  $check = Grid::find($o->grid);
  if ($check != null) JSONResponse::Error("The grid $o->grid already exists");

  $update = Grid::find($target->grid);
  $update->grid = $o->grid;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE grids SET grid=:grid, data_info=:info WHERE grid=:target',
    [
      'grid'=>$o->grid,
      'target'=>$target->grid,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Grid::delete($o->grid);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
