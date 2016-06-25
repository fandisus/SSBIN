<?php
if (!count($_POST)) { include DIR."/php/view/admin/landcover.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Landcover;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY landcover ASC";
  $p->landcover = Landcover::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Landcover::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->landcover) == '') JSONResponse::Error("Landcover cannot be empty");
  $old = Landcover::find($o->landcover);
  if ($old != null) JSONResponse::Error("The landcover $o->landcover already exists");

  $o = new Landcover($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->landcover == $target->landcover) JSONResponse::Error("Landcover unchanged");
  if (trim($o->landcover) == '') JSONResponse::Error("Landcover cannot be empty");
  
  $check = Landcover::find($o->landcover);
  if ($check != null) JSONResponse::Error("The landcover $o->landcover already exists");

  $update = Landcover::find($target->landcover);
  $update->landcover = $o->landcover;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE landcovers SET landcover=:landcover, data_info=:info WHERE landcover=:target',
    [
      'landcover'=>$o->landcover,
      'target'=>$target->landcover,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Landcover::delete($o->landcover);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
