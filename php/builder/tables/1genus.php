<?php
$t = new \Trust\TableComposer("genus");

$t->string("genus",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
