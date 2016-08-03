<?php
$pageTitle = "Species List";
include DIR.'/php/view/tulang.php';

function htmlHead() {
  ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <link rel="stylesheet" href="/jslib/viewerjs/viewer.min.css"/>
  <script src="/jslib/moment.min.js"></script>
  <script src="/js/common.js"></script>
  <script src="/jslib/jquery.redirect.js"></script>
  <script src="/js/common/specieslist.js"></script>
  <script src="/jslib/mine/DirPaging2.js"></script>
  <script src="/jslib/mine/degdms.js"></script>
  <script src="/jslib/mine/DirDegdms.js"></script>
  <script>
    $(document).ready(function() {
      $("input, select").addClass('form-control');
      $('#filter-table tbody').children().css({display:'none'});
    });
  </script>
  <style>
    #tabel-data td>img{max-height: 40px; max-width: 40px;}
    #tabel-data tr.invalidated {color:#ccc;}
    td { white-space: nowrap;}
    #modalShow td { white-space: normal;}
    .pagination { margin:5px 0;}
    
    tr-paging li:nth-child(7) { display: none;}
    .btn-sm.round { border-radius: 24px; }
    td .btn-xs { padding: 0px; margin: 0 2px; width: 18px; height: 18px; border-radius: 24px;}
  </style>
<?php }

function mainContent() {
  ?>
  <h2>Species Distribution</h2>
  <div class="row" ng-controller="ctrlFindings">
    <div class="col-md-12">
      <div class="form-group form-inline">
        <h4 style="display: inline-block;">Filter by:</h4>
        <select ng-model="filter" ng-options="f.text for f in pager.filterOptions"></select>
        &nbsp;<button ng-click="addFilter(filter)" class="btn btn-success btn-sm round"><i class="fa fa-plus fa-fw"></i></button>
      </div>
      <table class="form-group form-group-sm" id="filter-table">
        <tr id="localname">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Local Name </td><td><input type="text" placeholder="Local Name" ng-model="params.localname" /></td>
        </tr>
        <tr id="othername">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Other Name </td><td><input type="text" placeholder="Other Name" ng-model="params.othername" /></td>
        </tr>
        <tr id="class">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Class </td>
          <td><select ng-model="params.class" ng-options="c for c in classes"></select></td>
        </tr>
        <tr id="family">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Family </td><td><input type="text" placeholder="Family" ng-model="params.family" /></td>
        </tr>
        <tr id="genus">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Genus </td><td><input type="text" placeholder="Genus" ng-model="params.genus" /></td>
        </tr>
        <tr id="species">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Species </td><td><input type="text" placeholder="Species" ng-model="params.species" /></td>
        </tr>
        <tr id="commonname">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Common Name</td><td><input type="text" placeholder="Common Name" ng-model="params.commonname" /></td>
        </tr>
        <tr id="surveydate">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Survey Date </td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.startDate" /></td>
          <td>To</td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.endDate"/></td>
        </tr>
        <tr id="latitude">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Latitude </td><td><input type="text" placeholder="DMS / Deg" ng-model="params.startLat"/></td>
          <td>To</td><td><input type="text" placeholder="DMS / Deg" ng-model="params.endLat"/></td>
        </tr>
        <tr id="longitude">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Longitude </td><td><input type="text" placeholder="DMS / Deg" ng-model="params.startLong"/></td>
          <td>To</td><td><input type="text" placeholder="DMS / Deg" ng-model="params.endLong"/></td>
        </tr>
        <tr id="grid">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Grid</td><td><input type="text" placeholder="Grid" ng-model="params.grid" /></td>
        </tr>
        <tr id="village">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Village</td><td><input type="text" placeholder="Village" ng-model="params.village" /></td>
        </tr>
        <tr id="district">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>District </td>
          <td><select ng-model="params.district" ng-options="d for d in districts"></select></td>
        </tr>
        <tr id="landcover">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Landcover </td>
          <td><select ng-model="params.landcover" ng-options="l for l in landcovers"></select></td>
        </tr>
        <tr id="iucn_status">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>IUCN Status </td>
          <td><select ng-model="params.iucn_status" ng-options="s.abbr as s.long_name for s in iucns"></select></td>
        </tr>
        <tr id="cites_status">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>CITES Status</td><td><input type="text" placeholder="Village" ng-model="params.cites_status" /></td>
        </tr>
        <tr id="indo_status">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Indonesia Status </td>
          <td><select ng-model="params.indo_status" ng-options="s.abbr as s.long_name for s in indos"></select></td>
        </tr>
        <tr id="data_source">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Data Source</td><td><input type="text" placeholder="Village" ng-model="params.data_source" /></td>
        </tr>
        <tr id="reference">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Reference</td><td><input type="text" placeholder="Village" ng-model="params.reference" /></td>
        </tr>
        <tr id="other_info">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Other Info</td><td><input type="text" placeholder="Village" ng-model="params.other_info" /></td>
        </tr>
      </table>
      <tr-paging public="pager"></tr-paging>
      <button ng-click="showMap()" class="btn btn-default btn-xs">View on map <i class="fa fa-map-marker fa-fw"></i></button>
      <br /><br />
      <div class="table-responsive">
        <table id="tabel-data" class="table table-bordered table-striped table-condensed table-hover">
          <thead>
            <tr>
              <th>Pic</th>
              <th>ID</th>
              <th>Class</th>
              <th>Local Name</th>
              <th>Other Name</th>
              <th>N</th>
              <th>Family</th>
              <th>Genus</th>
              <th>Species</th>
              <th>Common Name</th>
              <th>Survey Month</th>
              <th>Survey Year</th>
              <th>Latitude</th>
              <th>Longitude</th>
              <th>Grid</th>
              <th>Village</th>
              <th>District</th>
              <th>Landcover</th>
              <th>IUCN Status</th>
              <th>CITES Status</th>
              <th>Indonesia Status</th>
              <th>Data Source</th>
              <th>Reference</th>
              <th>Other Information</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="o in findings" ng-click="show(o)" ng-class="{invalidated:!o.validation.validated}">
              <td><img ng-src="{{icon(o)}}" /></td>
              <td>{{o.id}}</td>
              <td>{{o.taxonomy.class}}</td>
              <td>{{o.localname}}</td>
              <td>{{o.othername}}</td>
              <td>{{o.n}}</td>
              <td>{{o.taxonomy.family}}</td>
              <td>{{o.taxonomy.genus}}</td>
              <td>{{o.taxonomy.species}}</td>
              <td>{{o.commonname}}</td>
              <td>{{getMonth(o.survey_date)}}</td>
              <td>{{getYear(o.survey_date)}}</td>
              <td>{{o.latitude}}</td>
              <td>{{o.longitude}}</td>
              <td>{{o.grid}}</td>
              <td>{{o.village}}</td>
              <td>{{o.district}}</td>
              <td>{{o.landcover}}</td>
              <td>{{o.iucn_status}}</td>
              <td>{{o.cites_status}}</td>
              <td>{{o.indo_status}}</td>
              <td>{{o.data_source}}</td>
              <td>{{o.reference}}</td>
              <td>{{o.other_info}}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <div id="init" style="display:none"><?php
    //class family district lancov iucn indo  genus species grids
    $init = new stdClass();
    $init->classes = \SSBIN\Classes::getList();
    $init->districts = \SSBIN\Location::getList();
    $init->landcovers = \SSBIN\Landcover::getList();
    $init->iucns = \SSBIN\IUCN_status::allWhere('ORDER BY abbr ASC', []);
    $init->indos = \SSBIN\Indo_status::allWhere('ORDER BY abbr ASC', []);
    $init->months = [
      ['num'=>1,'name'=>'January'],
      ['num'=>2,'name'=>'February'],
      ['num'=>3,'name'=>'March'],
      ['num'=>4,'name'=>'April'],
      ['num'=>5,'name'=>'May'],
      ['num'=>6,'name'=>'June'],
      ['num'=>7,'name'=>'July'],
      ['num'=>8,'name'=>'August'],
      ['num'=>9,'name'=>'September'],
      ['num'=>10,'name'=>'October'],
      ['num'=>11,'name'=>'November'],
      ['num'=>12,'name'=>'December']
    ]; //Kalau mau edit months ini, edit di sini, di users/findings, di expert/input
    $init->findings = \SSBIN\Finding::allWhere("WHERE validation->>'validated'='true' ORDER BY id DESC LIMIT 50", []);
    $init->totalItems = \SSBIN\Finding::countWhere("WHERE validation->>'validated'='true'", []);
    $init->paths = [
      'pic'=> \SSBIN\Finding::PICPATH,
      'thumb'=> \SSBIN\Finding::THUMBPATH,
      'icon'=> \SSBIN\Finding::ICONPATH
    ];
    echo json_encode($init);
    ?></div>
  <?php
}
