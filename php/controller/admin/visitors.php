<?php
const TYPES = ['All','With Post','Without Post'];
if (!count($_POST)) { include DIR."/php/view/admin/visitors.php"; die(); }

use Trust\JSONResponse;
use SSBIN\User;
$services = ['get','del'];
if (in_array($_POST['a'], $services)) $_POST['a']();

function get() {
  $s = \Trust\Forms::getPostObject('s');
  //from to type ip url a
  $from = \DateTime::createFromFormat("Y-m-d H:i:s", $s->from);
  $to = \DateTime::createFromFormat("Y-m-d H:i:s", $s->to);
  if (!$from) JSONResponse::Error("Invalid datetime from format");
  if (!$to) JSONResponse::Error("Invalid datetime to format");
  if ($from >= $to) JSONResponse::Error("Date from can't be later than date to");
  $diffDays = $to->diff($from)->days;
  //if ($diffDays > 7) JSONResponse::Error ("Please choose at max 7 days period");
  if (!in_array($s->type,TYPES)) JSONResponse::Error("Invalid type");
  
  //default: date only
  $wheres = []; $colVals = ['from'=>$s->from, 'to'=>$s->to];
  $wheres[] = "time BETWEEN :from AND :to";
  //type
  if ($s->type == 'With Post') {
    $wheres[] = "post IS NOT NULL";
  } elseif ($s->type == 'Without Post') {
    $wheres[] = "post IS NULL";
  }
  //ip
  if ($s->ip) {
    $wheres[] = "ip_address LIKE :ip";
    $colVals['ip']="%$s->ip%";
  }
  //url
  if ($s->url) {
    $wheres[] = "requested_url LIKE :url";
    $colVals['url']="%$s->url%";
  }
  //action
  if ($s->a) {
    $wheres[] = "post->>'a' LIKE :a";
    $colVals['a']="%$s->a%";
  }
  
  $strWhere = 'WHERE '.implode(" AND ", $wheres);
  $logs = \SSBIN\Logger::allWhere($strWhere, $colVals);
  JSONResponse::Success(["logs"=>$logs]);
}
function del() {
  $from = \DateTime::createFromFormat("Y-m-d H:i:s", $_POST['d']);
  if (!$from) JSONResponse::Error("Invalid date");
  $diff = time() - $from->getTimestamp();
  if ($diff < 3600*24*30) JSONResponse::Error ("You can only delete one month old data");
  
  \SSBIN\Logger::delWhere("WHERE time < :time", ['time'=>$_POST['d']]);
  JSONResponse::Success(["message"=>"Data deleted successfully"]);
}
