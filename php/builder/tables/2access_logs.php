<?php
$t = new \Trust\TableComposer("access_logs");

$t->string("ip_address", 50)->notNull()->index();
$t->timestamp('time')->index();
$t->string('requested_url',255)->notNull()->index();
$t->bigInteger('userid')->index()->foreign('users','id','CASCADE','CASCADE');
$t->jsonb('post');

$queries[] = $t->parse();
