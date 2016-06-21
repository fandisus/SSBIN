<?php
$t = new \Trust\TableComposer("families");

$t->string("family",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
