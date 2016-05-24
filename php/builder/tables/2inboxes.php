<?php
$t = new \Trust\TableComposer("inboxes");

$t->bigInteger('sender')->notNull()->foreign("users", "id","CASCADE","CASCADE");
$t->bigInteger("receiver")->notNull()->index()->foreign("users", "id","CASCADE","CASCADE");
$t->timestamp("time")->notNull();
$t->string("subject",255)->notNull();
$t->text("message")->notNull();

$t->primary(['sender','time']);

$queries[] = $t->parse();