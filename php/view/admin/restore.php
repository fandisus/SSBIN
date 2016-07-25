<?php
$pageTitle = "Database restore";
$pageSubTitle = "";
include $template;

function htmlHead() { ?>
  <script>
    var token = '<?= \Trust\Server::csrf_token(); ?>';
    $(document).ready(function() {
      $('#formRestore')
        .append($('<input/>').attr({type:'hidden',name:'a',value:'restore'}))
        .append($('<input/>').attr({type:'hidden',name:'token',value:token}));
    });
    var restore = function() {
      if (!confirm('Are you REALLY SURE?')) return;
      tr.postForm('/admin/database',$('#formRestore')[0], function(rep) {
        $.notify(rep.message,'success');
        $('#modalUpload').modal('hide');
      });
    };
  </script>
<?php }

function mainContent() { ?>
  <div class="alert alert-warning flex flex-vcenter">
    <i class="fa fa-warning fa-2x" style="margin: 0 5px 0 0"></i>
    This operation will overwrite the ENTIRE database. Use with caution.
  </div>
  
  <button class="btn btn-danger" data-toggle="modal" data-target="#modalUpload">
    <i class="fa fa-upload fa-fw"></i> Restore Database
  </button>
  
<div id="modalUpload" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Restore Database</h4>
      </div>
      <div class="modal-body">
        <form id="formRestore" enctype="multipart/form-data">
          <label>SSBIN file:</label>
          <input class="form-control" type="file" name="ssbin" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button onclick="restore()" type="button" class="btn btn-primary">Restore DB</button>
      </div>
    </div>
  </div>
</div>      
<?php }
