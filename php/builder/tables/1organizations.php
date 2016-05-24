<?php
$t = new \Trust\TableComposer("organizations");

$t->string("nama",50)->primary();
$t->string("category", 50)->index()->notNull();
$t->jsonb("data_info");

$queries[] = $t->parse();
