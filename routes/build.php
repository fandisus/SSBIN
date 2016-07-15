<?php
$builderPath = DIR.'/php/builder';
if ($paths[1] == "db") { include "$builderPath/database.php"; die(); }
if ($paths[1] == 'sb') { include "$builderPath/sandbox.php"; die(); }
if ($paths[1] == "seed") { include "$builderPath/seeder.php"; die(); }
if ($paths[1] == 'minifyjs') { include "$builderPath/minifyjs.php"; die(); }