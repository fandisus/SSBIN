app.requires.push('dndLists');
app.controller('ctrlFindings',function($scope) {
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
  var uri = "/users/findings";
  
  $scope.families=[]; $scope.genuses=[]; $scope.species=[];
  $scope.searchFamily = function(o) { if (o.taxonomy.family.trim() == '') return;
    var oPost = {a:'getFamilies',q:o.taxonomy.family,token:token};
    tr.silentPost(uri,oPost,function(rep) { $scope.families = rep.families; $scope.$apply(); });
  };
  $scope.searchGenus = function(o) { if (o.taxonomy.genus.trim() == '') return;
    var oPost = {a:'getGenuses',q:o.taxonomy.genus,token:token};
    tr.silentPost(uri,oPost,function(rep) { $scope.genuses = rep.genuses; $scope.$apply(); });
  };
  $scope.searchSpecies = function(o) { if (o.taxonomy.species.trim() == '') return;
    var oPost = {a:'getSpecies',q:o.taxonomy.species,token:token};
    tr.silentPost(uri,oPost,function(rep) { $scope.species = rep.species; $scope.$apply(); });
  };
  $scope.searchGrid = function(o) { if (o.grid.trim() == '') return;
    var oPost = {a:'getGrids',q:o.grid,token:token};
    tr.silentPost(uri,oPost,function(rep) { $scope.grids = rep.grids; $scope.$apply(); });
  };
  $scope.params = {startDate:null,endDate:null,startLat:null,endLat:null,startLong:null,endLong:null};
  $scope.pager = {uri:uri, totalItems:init.totalItems, moreParams:{more:$scope.params}};
  var fields = [
    {key:'id', text:'ID'},
    {key:'localname', text:'Local Name'},
    {key:'othername', text:'Other Name'},
    {key:"taxonomy->>'class'", text:'Class'},
    {key:"taxonomy->>'family'", text:'Family'},
    {key:"taxonomy->>'genus'", text:'Genus'},
    {key:"taxonomy->>'species'", text:'Species'},
    {key:'commonname', text:'Common Name'},
    {key:'latitude', text:'Latitude'},
    {key:'longitude', text:'Longitude'},
    {key:'grid', text:'Grid'},
    {key:'village', text:'Village'},
    {key:'district', text:'District'},
    {key:'landcover', text:'Landcover'},
    {key:'iucn_status', text:'IUCN Status'},
    {key:'indo_status', text:'Indonesia Status'},
    {key:'cites_status', text:'CITES Status'},
    {key:'data_source', text:'Data Source'}
  ]
  $scope.pager.filterOptions = fields;
  $scope.pager.orderOptions = fields;
  $scope.pager.pageChanged = function(rep) {
    $scope.findings = rep.findings;
    $scope.$apply();
  };
  
  $scope.getMonth = function(d) { return moment(d).format('MMMM'); };
  $scope.getYear = function(d) { return moment(d).format('YYYY'); };
  
  $scope.target = null; $scope.o = {};
  $scope.printDataInfo = printDataInfo;
  $scope.printValidationInfo = printValidationInfo;
  $('[data-toggle="popover"]').popover();
  
  var updateLatLongDMS = function() {
    setTimeout(function() {
      $scope.latinput.degChanged();
      $scope.longinput.degChanged();
      $scope.$apply();
    },5);
  };
  $scope.newO = function() {
    $scope.target = null;
    $scope.modalTitle = "New Findings";
    $scope.o = {taxonomy:{class:'',family:'',genus:'',species:''},localname:'',commonname:'',othername:'',n:0,survey_month:'',survey_year:'',latitude:null,longitude:null,grid:'',village:'',district:'',landcover:'',iucn_status:'',indo_status:'',data_source:'',reference:'',other_info:''};
    $("#modalEdit").modal('show');
    updateLatLongDMS();
  };
  $scope.edit = function(o) {
    $scope.target = o;
    $scope.modalTitle = "Edit findings with id:#" + o.id;
    $scope.o = angular.copy(o);
    $scope.o.survey_month=moment(o.survey_date).month()+1;
    $scope.o.survey_year=moment(o.survey_date).year();
    $("#modalEdit").modal('show');
    updateLatLongDMS();
  };
  $scope.saveChanges = function(o) {
    if ($scope.target == null) saveNew(o); else $scope.saveOld(o);
  };
  var saveNew = function(o) {
    var oPost = {a:'saveNew',o:o,token:token};
    tr.post(uri,oPost, function(rep) {
      $scope.findings.push(rep.new);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.saveOld = function(o) {
    var oPost = {a:'saveOld',o:o,target:$scope.target.id,token:token};
    tr.post(uri,oPost, function(rep) {
      $.extend($scope.target,rep.o);
      $.notify(rep.message,"success");
      $("#modalEdit").modal('hide');
      $scope.$apply();
    });
  };
  $scope.delete = function(o) {
    if (confirm("Are you sure you want to delete?\n\Findings with ID:#" + o.id) == false) return;
    var oPost={a:"delete",o:$scope.target.id,token:token};
    tr.post(uri,oPost, function(rep) {
      arrRemoveElement($scope.findings,$scope.target);
      $("#modalEdit").modal('hide');
      $.notify(rep.message,"success");
      $scope.$apply();
    });
  };
  $('#formSS')
    .append($('<input/>').attr({type:'hidden',name:'a',value:'upload_spreadsheet'}))
    .append($('<input/>').attr({type:'hidden',name:'token',value:token}));
  $scope.uploadSS = function() {
    tr.postForm(uri,$('#formSS')[0],function(rep) {
      for (var i in rep.findings) {
        if (typeof rep.findings[i] === 'function') continue;
        $scope.findings.push(rep.findings[i]);
      }
      $scope.$apply();
      $('#modalUpload').modal('hide');
    }, function (rep) {
      if (rep.data != undefined) console.log(rep.data);
    });
  };
  $('#picform')
    .append($('<input/>').attr({type:'hidden',name:'a',value:'savepic'}))
    .append($('<input/>').attr({type:'hidden',name:'token',value:token}));
  $scope.editPic = function(o,e) {
    $scope.target = o;
    $scope.o = angular.copy(o);
    $('.file-input img').attr('src','/pics/dropImageHere.png'); //Off from angularjs
    $('#modalPic').modal('show');
    e.stopPropagation();
  };
  $scope.uploadPic = function() {
    tr.postForm(uri,$('#picform')[0],function(rep) {
      $scope.target.pic.push(rep.pic);
      $scope.o.pic.push(rep.pic);
      $.notify(rep.message);
      $scope.$apply();
    });
  };
  $scope.icon = function(o) {
    if (o.pic.length === 0) return paths.pic + 'no-image.svg';
    return paths.icon + o.pic[0];
  };
  $scope.thumb = function(p) {
    return paths.thumb + p;
  };
  $scope.rem = function(p,idx) {
    var idx2 = $scope.o.pic.indexOf(p);
    if (idx > idx2) idx++;
    $scope.o.pic.splice(idx,1);
    console.clear();
  };
  $scope.delPic = function(p) {
    if (!confirm('Are you sure?')) return;
    var oPost={a:'delPic',p:p,target:$scope.o.id,token:token};
    tr.post(uri,oPost,function(rep) {
      arrRemoveElement($scope.target.pic,p);
      arrRemoveElement($scope.o.pic,p);
      $.notify(rep.message,'success');
      $scope.$apply();
    });
  };
  $scope.picReorder = function() {
    var oPost={a:'picReorder',pics:$scope.o.pic,target:$scope.o.id,token:token};
    tr.post(uri,oPost,function(rep) {
      $scope.target.pic = JSON.parse(JSON.stringify($scope.o.pic));
      $.notify(rep.message,'success');
      $scope.$apply();
    });
  };
  $scope.exportSS = function() {
    var pager = JSON.stringify({filterBy:$scope.pager.filterBy});
    var params = JSON.stringify($scope.params);
    var oPost = {a:'export_ss',pager:pager,more:params,token:token};
    $.redirect(uri,oPost,'POST');
  };
});
