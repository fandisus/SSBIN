<?php
if (!count($_POST)) { include DIR."/php/view/admin/organizations.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Organization;
use Trust\Forms;
$services = ['saveNew','saveOld','delete'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function saveNew() { global $login;
  $o = Forms::getPostObject('o');
  if ($o->category == "") JSONResponse::Error("Category cannot be empty");
  $old = Organization::where("WHERE category=:category AND name=:name", ['category'=>$o->category,'name'=>$o->name]);
  if ($old != null) JSONResponse::Error("The category and organization pair has already in the database");

  $o = new Organization($o);
  $o->save();
  
  JSONResponse::Success(['message'=>"Data successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  $target = Forms::getPostObject('target');
  if (trim($o->category) == '') JSONResponse::Error("Category cannot be empty");
  
  $o = new Organization($o);
  $o->setTimestamps();

  $colVals = [
    "newcat"=>$o->category,
    "newname"=>$o->name,
    'description'=>$o->description,
    "oldcat"=>$target->category,
    "oldname"=>$target->name,
    "data_info"=>json_encode($o->data_info)
  ];
  Trust\DB::exec( //data_info?
    "UPDATE organizations SET category=:newcat, name=:newname, description=:description, data_info=:data_info WHERE category=:oldcat AND name=:oldname", $colVals);
  unset ($colVals['data_info'],$colVals['description']);
  Trust\DB::exec(
    "UPDATE users SET category=:newcat, organization=:newname WHERE category=:oldcat AND organization=:oldname", $colVals);

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$o]);
}

function delete() {
  $o = Forms::getPostObject('o');
  $colVals = ["cat"=>$o->category,"org"=>$o->name];
  $u = SSBIN\User::where("WHERE category=:cat AND organization=:org",$colVals);
  if ($u != null) JSONResponse::Error("There are still users with corresponding category and organization");
  
  Organization::delWhere("WHERE category=:cat AND name=:org",$colVals);
  JSONResponse::Success(["message"=>'Category/Organization deleted successfully']);
}
