<?php
$pageTitle = "Database backup";
$pageSubTitle = "";
include $template;

function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/jquery.redirect.js"></script>
  <script>
    var backup = function() {
      $.redirect('/admin/database',{a:'backup',token:token},'POST');
    };
  </script>
<?php }

function mainContent() { ?>
  <div class="alert alert-warning flex flex-vcenter" style='max-width: 500px;'>
    <span>
      <i class="fa fa-warning fa-2x" style="margin: 0 5px 0 0"></i>
    </span>
    <span>
      The backup file does not contain images in the data. To back up the images,
      use SFTP to copy the <code>/public/images/userpic</code> and <code>/public/pics</code> folder. Don't forget to rename the images backup folder to the related backup files.
    </span>
  </div>
  
  <button onclick="backup()" class="btn btn-success"><i class="fa fa-database fa-fw"></i> Backup</button>
<?php }
