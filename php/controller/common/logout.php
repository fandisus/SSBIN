<?php
unset ($_SESSION['login']);
setcookie("logtok",'basing',time()-1);
header('location:/');
