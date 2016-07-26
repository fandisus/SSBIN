<?php
$pageTitle = "Users Home";
include $template;

function htmlHead() {
  
}

function mainContent() { global $login;
  if (!$login->active) { ?>
<div class="alert alert-warning flex flex-vcenter" style="max-width: 500px;">
  <i class="fa fa-warning fa-fw"></i>
  Account has not been activated. Please check email for activation link.
</div>
  <?php }
  echo "halaman home: inbox, confirmed data input<br />For experts: New validation requests";
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

