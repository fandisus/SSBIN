<?php
switch ($paths[0]) {
 case 'ctrl': include 'ctrl.php';break;
 case 'users': include 'users.php';break;
 case 'build': include 'build.php';break; //TODO: Comment this on production
 default: include 'common.php'; break;
}
