app.controller('ctrlPages',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.pages = init.pages;
  var uri = "/admin/pages";
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $('[data-toggle="popover"]').popover();
  
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Page";
    $scope.o = {name:'', position:'footer', group_name:'', order_no:''};
    $("#summernote").summernote('code','');
    $("#modalEdit").modal('show');
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit page: " + o.name + '(' + o.group_name + ')';
    $scope.o = angular.copy(o);
    tr.post(uri, {a:'getContent',id:o.id,token:token}, function(rep) {
      $("#summernote").summernote('code',rep.content);
      $scope.$apply();
      $("#modalEdit").modal('show');
    });
  };
  $scope.saveChanges = function(o) {
    o.content = $("#summernote").summernote('code');
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.pages.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.target.name = rep.o.name;
      //$scope.target.position = rep.o.position;  //No Need, always footer. Or will not able to change
      $scope.target.group_name = rep.o.group_name;
      $scope.target.order_no = rep.o.order_no;
      $scope.target.data_info = rep.o.data_info;
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Page: " + o.name) == false) return;
    var oPost={a:"delete",o:$scope.target,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.pages,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
}]);
