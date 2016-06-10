<?php
unset ($_SESSION['login']);
setcookie("login",'basing',time()-1);
header('location:/');
