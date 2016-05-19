<?php
$t = new \Trust\TableComposer("sysadmin");

$t->increments("id")->notNull()->primary();
$t->string("username",255)->notNull()->unique();
$t->string("password",100)->notNull();
$t->string('email',255)->notNull()->index();
$t->jsonb('access_right');
$t->jsonb("data_info")->comment('date_created, created_by, last_login, activation_link, activation_expiry');
$t->bool('activated')->index();

$queries[] = $t->parse();
