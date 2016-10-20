<?php
$pageTitle = "Expert Dashboard";
$pageSubTitle = "";
include $template;

function mainContent() { global $login; ?>
<h1>Welcome <b><?= $login->username ?></b> to Expert menu</h1>
<?php }
