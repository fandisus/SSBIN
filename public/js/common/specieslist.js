app.controller('ctrlFindings',['$scope',function($scope) {
  var init = JSON.parse($("#init").html());
  var paths = init.paths;
  $scope.classes = init.classes;
  $scope.districts = init.districts;
  $scope.landcovers = init.landcovers;
  $scope.iucns = init.iucns;
  $scope.indos = init.indos;
  $scope.months = init.months;
  
  $scope.latinput = {type:'lat',mode:'dms'};
  $scope.longinput = {type:'long',mode:'dms'};
  
  $scope.findings = init.findings;
  var uri = "/specieslist";
  
  $scope.o = {};
  $scope.params = {};
  $scope.pager = {uri:uri, totalItems:init.totalItems, moreParams:{more:$scope.params}};
  var fields = [
    {key:'localname', text:'Local Name'},
    {key:'othername', text:'Other Name'},
    {key:"class", text:'Class'},
    {key:"family", text:'Family'},
    {key:"genus", text:'Genus'},
    {key:"species", text:'Species'},
    {key:'commonname', text:'Common Name'},
    {key:'surveydate', text:'Survey Date'},
    {key:'district', text:'District'},
    {key:'landcover', text:'Landcover'},
    {key:'iucn_status', text:'IUCN Status'},
    {key:'indo_status', text:'Indonesia Status'},
    {key:'cites_status', text:'CITES Status'},
    {key:'data_source', text:'Data Source'},
    {key:'reference', text:'Reference'},
    {key:'other_info', text:'Other Information'}
  ];
  $scope.pager.filterOptions = fields;
  $scope.pager.orderOptions = fields;
  $scope.filter = null;
  $scope.addFilter = function(f) {
    $('#'+f.key).css('display','');
    if (f.key==='surveydate') $scope.params.startDate=null,$scope.params.endDate=null;
    else $scope.params[f.key]=null;
  };
  $scope.removeFilter = function(e) {
    var row = e.delegateTarget.parentElement.parentElement;
    row.style.display='none';
    var key = row.getAttribute('id');
    if (key==='surveydate') delete $scope.params.startDate,delete $scope.params.endDate;
    else delete $scope.params[key];
  };
  $scope.pager.pageChanged = function(rep) {
    $scope.findings = rep.findings;
    $scope.$apply();
  };
  
  $scope.getMonth = function(d) { return moment(d).format('MMMM'); };
  $scope.getYear = function(d) { return moment(d).format('YYYY'); };
  
  $scope.show = function(o) {
    window.open(uri + '?id='+o.id);
  };
  $scope.showMap = function() {
    $.redirect(uri,{a:'showMap',more:JSON.stringify($scope.params),token:token},'POST');
  };
    
  $scope.icon = function(o) {
    if (o.pic.length === 0) return paths.pic + 'no-image.svg';
    return paths.icon + o.pic[0];
  };
}]);