<?php
$t = new \Trust\TableComposer("locations");

$t->string("location",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
