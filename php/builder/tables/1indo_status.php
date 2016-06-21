<?php
$t = new \Trust\TableComposer("indo_status");

$t->string("abbr",50)->primary();
$t->string("long_name", 50)->index()->notNull();
$t->jsonb("data_info");

$queries[] = $t->parse();
