<?php
if (!count($_POST)) { include DIR."/php/view/admin/home.php"; die(); }

use Trust\JSONResponse;

$services = [];
if (in_array($_POST['a'], $services)) $_POST['a']();
