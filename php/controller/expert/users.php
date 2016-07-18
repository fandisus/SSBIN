<?php
if (!count($_POST)) { include DIR."/php/view/expert/users.php"; die(); }

$services = ['toggleValidation'];
if (in_array($_POST['a'], $services)) { include DIR.'/php/controller/admin/users.php'; die(); }
//Note: Biar controller admin yang ngurus. Jadi sesuai DRY.