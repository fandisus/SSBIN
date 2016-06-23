<?php
if (!count($_POST)) { include DIR."/php/view/admin/families.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Families;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY family ASC";
  $p->families = Families::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Families::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->family) == '') JSONResponse::Error("Family cannot be empty");
  $old = Families::find($o->family);
  if ($old != null) JSONResponse::Error("The family $o->family already exists");

  $o = new Families($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->family == $target->family) JSONResponse::Error("Family unchanged");
  if (trim($o->family) == '') JSONResponse::Error("Family cannot be empty");
  
  $check = Families::find($o->family);
  if ($check != null) JSONResponse::Error("The family $o->family already exists");

  $update = Families::find($target->family);
  $update->family = $o->family;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE families SET family=:family, data_info=:info WHERE family=:target',
    [
      'family'=>$o->family,
      'target'=>$target->family,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Families::delete($o->family);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Category/Organization deleted successfully']);
}
