<?php
$t = new \Trust\TableComposer("classes");

$t->string("class",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
