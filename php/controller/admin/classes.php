<?php
if (!count($_POST)) { include DIR."/php/view/admin/classes.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Classes;
use Trust\Forms;

$services = ['saveNew','saveOld','delete'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function saveNew() { global $login;
  $o = Forms::getPostObject('o');
  if (trim($o->class) == '') JSONResponse::Error("Class cannot be empty");
  $old = Classes::find($o->class);
  if ($old != null) JSONResponse::Error("The class $o->class already exists");

  $o = new Classes($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if ($o->class == $target->class) JSONResponse::Error("Class unchanged");
  if (trim($o->class) == '') JSONResponse::Error("Class cannot be empty");
  
  $check = Classes::find($o->class);
  if ($check != null) JSONResponse::Error("The class $o->class already exists");

  $update = Classes::find($target->class);
  $update->class = $o->class;
  $update->setTimestamps();
  //Note: Belum bisa $update->save() karena mau update PK juga.
  \Trust\DB::exec('UPDATE classes SET class=:class, data_info=:info WHERE class=:target',
    [
      'class'=>$o->class,
      'target'=>$target->class,
      'info'=>json_encode($update->data_info)
    ]);
  //TODO: Update data findings

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$update]);
}

function delete() {
  $o = Forms::getPostObject('o');
  
  Classes::delete($o->class);
  //TODO: setnull data findings
  JSONResponse::Success(["message"=>'Category/Organization deleted successfully']);
}
