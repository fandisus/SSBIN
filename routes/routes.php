<?php
switch ($paths[0]) {
 case 'admin': include 'admin.php';break;
 case 'users': include 'users.php';break;
 case 'expert': include 'expert.php';break;
 case 'build': include 'build.php';break; //TODO: Comment this on production
 default: include 'common.php'; break;
}
