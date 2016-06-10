<?php
$t = new \Trust\TableComposer("users");

$t->bigIncrements("id")->primary();
$t->string("username",100)->unique()->index();
$t->string("password",100);
$t->jsonb("biodata")->ginPropIndex(['city','state','name','email','gender','dob']);
$t->string("category",100)->index()->notNull();
$t->string("organization",100)->index();
$t->jsonb("data_info");
$t->jsonb("login_info");
$t->jsonb("login_sys")->ginPropIndex(["activation_code","remember_token","forgot_token"]);
$t->bool("active")->index();

$t->string("level",20)->notNull()->index()->comment("Standard/Expert/Admin");
$t->jsonb("expertise")->ginIndex();
$t->bool('validated')->index();

$t->comment("biodata","name, email, city, state, phone, gender, dob, profile_pic");
$t->comment("login_info","join_date,last_login");
$t->comment("login_sys","activation_code,code_expiry,remember_token,remember_expiry,forgot_token,forgot_expiry");

$queries[] = $t->parse();
