<?php
$t = new \Trust\TableComposer("pages");

$t->increments("id")->primary();
$t->string("name",50)->index();
$t->string("position",50)->index(); //menu or footer
$t->string("group_name", 50)->index();
$t->integer("order_no")->index()->notNull();
$t->text("content");
$t->jsonb("data_info");

$queries[] = $t->parse();
