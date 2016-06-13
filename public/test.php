<?php
include "../phplib/autoload.php";

$users = \SSBIN\User::all();
$u = $users[0];

if (is_object($u->data_info)) echo "yes"; else echo "no";
//Trust\Debug::print_r($u->data_info);

