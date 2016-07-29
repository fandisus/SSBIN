<?php
include DIR.'/php/view/tulang.php';
function htmlHead() { ?>
<script>
  $(document).ready(function() {
    $('.frmInput input, .frmInput textarea').addClass('form-control');
  });
  var token = '<?= \Trust\Server::csrf_token() ?>';
  app.controller('ctrlContact',['$scope',function($scope) {
    var uri = '/contactus';
    $scope.contact = {email:'',name:'',subject:'',message:''};
    $scope.send = function() {
      var oPost = {a:'contactus',contact:$scope.contact,token:token};
      tr.post(uri, oPost, function(rep) {
        $.notify(rep.message);
      });
    };
  }]);
</script>
<style>
  .frmInput input, .frmInput textarea { margin-bottom: 5px; }
  .frmInput textarea { min-height: 100px;}
  table { min-width: 300px;}
</style>
<?php }

function mainContent() { ?>
<h3>Contact us</h3>
<div class="frmInput" ng-controller='ctrlContact'>
  <table>
    <tr>
      <td>Your E-mail</td>
      <td><input type="text" ng-model='contact.email'/></td>
    </tr>
    <tr>
      <td>Your name</td>
      <td><input type="text" ng-model='contact.name'/></td>
    </tr>
    <tr>
      <td>Subject</td>
      <td><input type="text" ng-model='contact.subject'/></td>
    </tr>
    <tr>
      <td>Message</td>
      <td><textarea ng-model='contact.message'></textarea></td>
    </tr>
    <tr>
      <td></td>
      <td><button ng-click="send()" class="btn btn-primary">Send message</button></td>
    </tr>
  </table>
</div>
<?php }
