<?php
if (!count($_POST)) { include DIR."/php/view/admin/pages.php"; die(); }

use Trust\JSONResponse;
use SSBIN\Pages;
use Trust\Forms;

$services = ['saveNew','saveOld','delete','getContent'];
if (in_array($_POST['a'], $services)) $_POST['a'](); else JSONResponse::Error("Service unavailable");

function getContent() {
  $id = Forms::getPostObject('id');
  $page = \SSBIN\Pages::find($id);
  $content = $page->content;
  JSONResponse::Success(["content"=>$content]);
}

function saveNew() {
  $o = Forms::getPostObject('o');
  if (trim($o->name) == '') JSONResponse::Error("Page name cannot be empty");
  $old = Pages::where("WHERE name=:name", ['name'=>$o->name], "id");
  if ($old != null) JSONResponse::Error("Page with name \"$o->name\" already exists");
  if (trim($o->position) != 'footer') JSONResponse::Error("Page position error");
  if (trim($o->group_name) == '') JSONResponse::Error("Page group name cannot be empty");
  if (trim($o->order_no) == '') JSONResponse::Error("Page order cannot be empty");
  if (strlen($o->content) < 30) JSONResponse::Error("Content too short");
  
  //http://stackoverflow.com/questions/7130867/remove-script-tag-from-html-content
  $o->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $o->content);

  $o = new Pages($o);
  $o->insert();

  JSONResponse::Success(['message'=>"Page successfully added", "new"=>$o]);
}

function saveOld() {
  $o = Forms::getPostObject('o');
  if ($o->id == 1 && $o->name != 'About SSBIN') JSONResponse::Error("Cannot change \"About SSBIN\" page name");
  if (trim($o->name) == '') JSONResponse::Error("Page name cannot be empty");
  $old = Pages::find($o->id);
  if ($old->name != $o->name) {
    $check = Pages::where("WHERE name=:name", ['name'=>$o->name], "id");
    if ($check != null) JSONResponse::Error("Page with name \"$o->name\" already exists");
  }
  if ($o->position != $old->position) JSONResponse::Error("Page position error");
  if ($o->position != "menubar" && trim($o->group_name) == '') JSONResponse::Error("Page group name cannot be empty");
  if (trim($o->order_no) == '') JSONResponse::Error("Page order cannot be empty");
  if (strlen($o->content) < 30) JSONResponse::Error("Content too short");
  
  //http://stackoverflow.com/questions/7130867/remove-script-tag-from-html-content
  $o->content = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $o->content);

  foreach ($o as $k=>$v) $old->$k = $v;
  $old->setTimestamps();
  $old->update();

  JSONResponse::Success(['message'=>"Data successfully updated",'o'=>$old]);
}

function delete() {
  $o = Forms::getPostObject('o');
  if ($o->id == 1) JSONResponse::Error('Can not delete about page');
  
  Pages::delete($o->id);
  JSONResponse::Success(["message"=>'Data deleted successfully']);
}
