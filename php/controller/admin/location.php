<?php
if (!count($_POST)) { include DIR."/php/view/admin/location.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Location;
use Trust\Forms;
use Trust\Pager;

$services = ['saveNew','saveOld','delete','get'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

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
