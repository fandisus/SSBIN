app.controller('ctrlVisit', function($scope) {
  var init = JSON.parse($("#init").html());
  $scope.logs = init.logs;
  $scope.types = init.types;
  
  $scope.pager = {
    itemsPerPage:20,
    currentPage:1,
    totalItems:init.logs.length,
  };
  ($scope.setPages = function() {
    var pager = $scope.pager;
    console.log(pager);
    pager.totalPages = Math.ceil(pager.totalItems / pager.itemsPerPage);
    pager.pages = [];
    for (var i=pager.currentPage-2; i<= pager.currentPage+2; i++) {
      if (i>=1 && i<=pager.totalPages) pager.pages.push(i);
    }
    pager.startRecord = (pager.totalItems == 0) ? 0 : (pager.currentPage-1) * pager.itemsPerPage+1;
    pager.endRecord = (pager.currentPage == pager.totalPages) 
                      ? pager.totalItems : pager.currentPage * pager.itemsPerPage;
  })();
  $scope.changePage = function(p) {
    if (p<1 || p>$scope.pager.totalPages) return;
    $scope.pager.currentPage=p;
    $scope.setPages();
  };
  
  var dateFormat = 'YYYY-MM-DD HH:mm:ss';
  var dayStart = moment().add(-1,'days').startOf('day').format(dateFormat);
  var dayEnd = moment().add(-1,'days').endOf('day').format(dateFormat);
  $scope.search = {from:dayStart, to:dayEnd,type:"All",ip:null,url:null,a:null};
  var lastMonth = moment().add(-1,'months').startOf('day').format(dateFormat);
  $scope.delDate = lastMonth;
  
  $scope.log = {};
  $scope.showPost = function(l) {
    $scope.log = l;
    $("#modalShowPostData").modal('show');
  };
  $scope.momenter = function(d) {
    return moment(d).calendar();
  };
  $scope.stringify = function(d) {
    return JSON.stringify(d,undefined,2);
  };
  $scope.view = function() {
    tr.post('/admin/visitors',{a:'get',s:$scope.search, token:token},function(rep) {
      $scope.logs = rep.logs;
      $scope.pager.totalItems = rep.logs.length;
      $scope.pager.currentPage = 1;
      $scope.setPages();
      $scope.$apply();
      $("#modalAdvSearch").modal('hide');
    });
  };
  $scope.del = function() {
    if (!confirm('Are you sure?\nThis action cannot be undone')) return;
    tr.post('/admin/visitors',{a:'del',d:$scope.delDate,token:token}, function(rep) {
      $.notify(rep.message,'success');
      $("#modalDelete").modal('hide');
    });
  };
}).filter('startFrom', function() {
  return function(input, start) {
    if (input) {start = +start; return input.slice(start); }
    return [];
  };
});
