<?php
$t = new \Trust\TableComposer("landcovers");

$t->string("landcover",50)->primary();
$t->jsonb("data_info");

$queries[] = $t->parse();
