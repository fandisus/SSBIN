<?php
session_start();
include "../phplib/autoload.php";

$arr = [
  'b'=>'jum\'at',
  'a'=>1,
  'c'=>3
];
echo json_encode($arr);