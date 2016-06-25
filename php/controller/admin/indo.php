<?php
if (!count($_POST)) { include DIR."/php/view/admin/indo.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Indo_status;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function get() {
  $p = Pager::GetQueryAttributes();
  $p->strOrder = ($p->strOrder != "") ? $p->strOrder : " ORDER BY abbr ASC";
  $p->statuses = Indo_status::allWhere("$p->strWhere $p->strOrder $p->strLimit", []);
  $p->totalItems = Indo_status::countWhere($p->strWhere, []);
  
  JSONResponse::Success((array)$p);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->abbr) == '') JSONResponse::Error("Abbreviation cannot be empty");
  if (trim($o->long_name) == '') JSONResponse::Error("Indonesia Status cannot be empty");
  $old = Indo_status::find($o->abbr);
  if ($old != null) JSONResponse::Error("The code $o->abbr already exists");

  $o = new Indo_status($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->abbr == $target->abbr && $o->long_name == $target->long_name) JSONResponse::Error("Data unchanged");
  if (trim($o->abbr) == '') JSONResponse::Error("Abbreviation cannot be empty");
  if (trim($o->long_name) == '') JSONResponse::Error("IUCN Status cannot be empty");
  
  $check = Indo_status::where('WHERE abbr=:new AND abbr<>:old', ['new'=>$o->abbr,'old'=>$target->abbr]);
  if ($check != null) JSONResponse::Error("The code $o->abbr already exists");

  $update = Indo_status::find($target->abbr);
  $update->abbr = $o->abbr;
  $update->long_name = $o->long_name;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE indo_status SET abbr=:abbr, long_name=:long_name, data_info=:info WHERE abbr=:target',
    [
      'abbr'=>$o->abbr,
      'long_name'=>$o->long_name,
      'target'=>$target->abbr,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Indo_status::delete($o->abbr);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
