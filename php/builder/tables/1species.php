<?php
$t = new \Trust\TableComposer("species");

$t->string("species",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
