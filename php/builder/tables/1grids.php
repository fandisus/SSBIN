<?php
$t = new \Trust\TableComposer("grids");

$t->string("grid",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
