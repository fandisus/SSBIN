<?php
if (!count($_POST)) { include DIR."/php/view/admin/species.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Species;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY species ASC";
  $p->species = Species::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Species::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->species) == '') JSONResponse::Error("Species cannot be empty");
  $old = Species::find($o->species);
  if ($old != null) JSONResponse::Error("The species $o->species already exists");

  $o = new Species($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->species == $target->species) JSONResponse::Error("Species unchanged");
  if (trim($o->species) == '') JSONResponse::Error("Species cannot be empty");
  
  $check = Species::find($o->species);
  if ($check != null) JSONResponse::Error("The species $o->species already exists");

  $update = Species::find($target->species);
  $update->species = $o->species;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE species SET species=:species, data_info=:info WHERE species=:target',
    [
      'species'=>$o->species,
      'target'=>$target->species,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Species::delete($o->species);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
