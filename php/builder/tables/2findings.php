<?php
$t = new \Trust\TableComposer("findings");

$t->bigIncrements('id')->primary();
$t->jsonb('pic');
$t->string('localname', 50)->index();
$t->string('othername', 50)->index();
$t->integer('n')->notNull();
$t->jsonb('taxonomy')->ginPropIndex(['class','family','genus','species']);
$t->string('commonname', 50)->index();
$t->date('survey_date')->index();
$t->date('date_precision')->index();
$t->double('latitude')->index();
$t->double('longitude')->index();
$t->string('village',50)->index();
$t->string('district',50)->index();
$t->string('landcover',50)->index();
$t->string('iucn_status',50)->index();
$t->string('indo_status',50)->index();
$t->string('cites_status',50)->index();
$t->string('data_source',50)->index();
$t->string('reference',100);
$t->text('other_info');
$t->jsonb('data_info')->ginPropIndex(['created_by']); //Yang nginput boleh download datanyo dewek
$t->jsonb('validation')->ginPropIndex(['validated','validated_by']);
//{validated:t/f,validated_by:...,validated_at:...}

  //http://stackoverflow.com/questions/22316348/converting-degree-minutes-seconds-dms-to-decimal-in-php
//http://www.phpclasses.org/browse/file/10671.html

$queries[] = $t->parse();
