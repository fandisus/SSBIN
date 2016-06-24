<?php
if (!count($_POST)) { include DIR."/php/view/admin/genus.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Genus;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY genus ASC";
  $p->genus = Genus::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Genus::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->genus) == '') JSONResponse::Error("Genus cannot be empty");
  $old = Genus::find($o->genus);
  if ($old != null) JSONResponse::Error("The genus $o->genus already exists");

  $o = new Genus($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->genus == $target->genus) JSONResponse::Error("Genus unchanged");
  if (trim($o->genus) == '') JSONResponse::Error("Genus cannot be empty");
  
  $check = Genus::find($o->genus);
  if ($check != null) JSONResponse::Error("The genus $o->genus already exists");

  $update = Genus::find($target->genus);
  $update->genus = $o->genus;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE genus SET genus=:genus, data_info=:info WHERE genus=:target',
    [
      'genus'=>$o->genus,
      'target'=>$target->genus,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Genus::delete($o->genus);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
