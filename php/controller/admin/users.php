<?php
if (!count($_POST)) { include DIR."/php/view/admin/users.php"; die(); }

use Trust\JSONResponse;
use SSBIN\User;
$services = ['del'];
if (in_array($_POST['a'], $services)) $_POST['a']();
