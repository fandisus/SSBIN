<?php
$pageTitle = "Findings data validation";
$pageSubTitle = "Input Findings";
include $template;

function htmlHead() {
  ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/jslib/mine/file-input.js"></script>
  <script src="/jslib/jquery.redirect.js"></script>
  <script src="/js/common.js"></script>
  <script src="/jslib/angular-drag-and-drop-lists.min.js"></script>
  <script src="/js/expert/input.js"></script>
  <script src="/jslib/mine/DirPaging2.js"></script>
  <script src="/jslib/mine/degdms.js"></script>
  <script src="/jslib/mine/DirDegdms.js"></script>
  <script>
    $(document).ready(function() {
      $("input, select").addClass('form-control');
    });
  </script>
  <style>
    #tabel-data td>img{max-height: 40px; max-width: 40px;}
    #tabel-data tr.invalidated {color:#ccc;}
    ul.pic-list { padding: 0; list-style-type: none;}
    .pic-list li { position:relative; top:0px; left:0px; vertical-align: top;
      display:inline-block; width: 170px; height: 170px;
      padding:5px; margin:3px; text-align:center;
      border: 1px solid gray; border-radius: 3px;
    }
    .pic-list button { position: absolute; top:0px; right:0px; }
    td { white-space: nowrap;}
    td>input.form-control, td>select.form-control { width:100%;}
    .form-group { margin-bottom: 0px;}
    .pagination { margin:5px 0;}
    #modalEdit table td i { color: #E00; font-size: 0.9em }
  </style>
<?php }

function mainContent() {
  ?>
  <div class="row" ng-controller="ctrlFindings">
    <div class="col-md-12">
      <table class="form-group form-group-sm">
        <tr>
          <td>Survey Date </td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.startDate" /></td>
          <td>To :</td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.endDate"/></td>
        </tr>
        <tr>
          <td>Latitude </td><td><input type="text" placeholder="DMS / Deg" ng-model="params.startLat"/></td>
          <td>To</td><td><input type="text" placeholder="DMS / Deg" ng-model="params.endLat"/></td>
        </tr>
        <tr>
          <td>Longitude </td><td><input type="text" placeholder="DMS / Deg" ng-model="params.startLong"/></td>
          <td>To</td><td><input type="text" placeholder="DMS / Deg" ng-model="params.endLong"/></td>
        </tr>
      </table>
      <tr-paging public="pager"></tr-paging><br />
      <button ng-click="exportSS()" class="btn btn-primary">Download as spreadsheet <i class="fa fa-download fa-fw"></i></button>
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
            <tr ng-repeat="o in findings" ng-click="edit(o)" ng-class="{invalidated:!o.validation.validated}">
              <td><img ng-click="editPic(o,$event);" ng-src="{{icon(o)}}" /></td>
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

<div id="modalPic" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Manage pictures</h4>
      </div>
      <div class="modal-body">
        <form id='picform' enctype="multipart/form-data">
          <input name="target" type="hidden" ng-value="o.id"/>
          <label for="pic">Choose pic to upload</label>
          <div class='file-input' style="margin-bottom: 8px;">
            <img/>
            <input type="file" name='pic'/>
          </div>
          <button ng-click="uploadPic()" class="btn btn-success"><i class="fa fa-upload"></i> Upload pic</button>
        </form>
        <ul dnd-list="o.pic" class="pic-list">
          <li ng-repeat="p in o.pic"
               dnd-draggable="p"
               dnd-moved="rem(p,$index)"
               dnd-effect-allowed="move">
            <img ng-src="{{thumb(p)}}"/>
            <button ng-click="delPic(p)" class="btn btn-default btn-xs">&times;</button>
          </li>
        </ul>
        <button ng-click="picReorder()" class="btn btn-primary">Save pics order</button>
      </div>
    </div>
  </div>
</div>      


      
      
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{modalTitle}}</h4>
      </div>
      <div class="modal-body">
        <table class="table table-condensed table-striped form-group form-group-sm">
          <tr>
            <td>ID <i>(ID)</i></td>
            <td><input type="text" ng-model="o.id" readonly="readonly"/></td>
          </tr>
          <tr>
            <td>Class * <i>(Kelas)</i></td>
            <td><input type="text" ng-model="o.taxonomy.class" list="list-class"/></td>
          </tr>
          <tr>
            <td>Local Name * <i>(Nama Lokal)</i></td>
            <td><input type="text" ng-model="o.localname"/></td>
          </tr>
          <tr>
            <td>Other Name <i>(Nama Lain)</i></td>
            <td><input type="text" ng-model="o.othername"/></td>
          </tr>
          <tr>
            <td>N * <i>(Jumlah)</i></td>
            <td><input type="text" ng-model="o.n"/></td>
          </tr>
          <tr>
            <td>Family <i>(Famili)</i></td>
            <td><input type="text" ng-model="o.taxonomy.family" list="list-family" ng-change="searchFamily(o)"/></td>
          </tr>
          <tr>
            <td>Genus <i>(Genus)</i></td>
            <td><input type="text" ng-model="o.taxonomy.genus" list="list-genus" ng-change="searchGenus(o)"/></td>
          </tr>
          <tr>
            <td>Species <i>(Spesies)</i></td>
            <td><input type="text" ng-model="o.taxonomy.species" list="list-species" ng-change="searchSpecies(o)"/></td>
          </tr>
          <tr>
            <td>Common Name <i>(Nama Umum)</i></td>
            <td><input type="text" ng-model="o.commonname"/></td>
          </tr>
          <tr>
            <td>Survey Month <i>(Bulan Survey)</i></td>
            <td><select ng-model="o.survey_month" ng-options="m.num as m.name for m in months" /></td>
          </tr>
          <tr>
            <td>Survey Year <i>(Tahun Survey)</i></td>
            <td><input type="text" ng-model="o.survey_year"/></td>
          </tr>
          <tr>
            <td>Latitude <i>(Latitude)</i></td>
            <td><tr-degdms deg="o.latitude" public='latinput' type="lat" mode="dms"></tr-degdms></td>
          </tr>
          <tr>
            <td>Longitude <i>(Longitude)</i></td>
            <td><tr-degdms deg="o.longitude" public='longinput' type="long" mode="dms"></tr-degdms></td>
          </tr>
          <tr>
            <td>Grid <i>(Grid)</i></td>
            <td><input type="text" ng-model="o.grid" list="list-grid" ng-change="searchGrid(o)"/></td>
          </tr>
          <tr>
            <td>Village <i>(Desa)</i></td>
            <td><input type="text" ng-model="o.village"/></td>
          </tr>
          <tr>
            <td>District <i>(Kabupaten)</i></td>
            <td><input type="text" ng-model="o.district" list="list-district"/></td>
          </tr>
          <tr>
            <td>Landcover <i>(Tutupan Lahan)</i></td>
            <td><input type="text" ng-model="o.landcover" list="list-landcover"/></td>
          </tr>
          <tr>
            <td>IUCN Status <i>(Status IUCN)</i></td>
            <td><select ng-model="o.iucn_status" ng-options="s.abbr as s.long_name for s in iucns"></select></td>
          </tr>
          <tr>
            <td>CITES Status <i>(Status CITES)</i></td>
            <td><input type="text" ng-model="o.cites_status" list="list-indo"/></td>
          </tr>
          <tr>
            <td>Indonesia Status <i>(Status Indonesia)</i></td>
            <td><select ng-model="o.indo_status" ng-options="s.abbr as s.long_name for s in indos"/></td>
          </tr>
          <tr>
            <td>Data Source <i>(Sumber Data)</i></td>
            <td><input type="text" ng-model="o.data_source"/></td>
          </tr>
          <tr>
            <td>Reference <i>(Referensi)</i></td>
            <td><input type="text" ng-model="o.reference"/></td>
          </tr>
          <tr>
            <td>Other Information <i>(Info lain)</i></td>
            <td><input type="text" ng-model="o.other_info"/></td>
          </tr>
          <datalist id="list-class"><option ng-repeat="c in classes" value="{{c}}"/></datalist>
          <datalist id="list-family"><option ng-repeat="f in families" value="{{f}}"/></datalist>
          <datalist id="list-genus"><option ng-repeat="g in genuses" value="{{g}}"/></datalist>
          <datalist id="list-species"><option ng-repeat="s in species" value="{{s}}"/></datalist>
          <datalist id="list-grid"><option ng-repeat="g in grids" value="{{g}}"/></datalist>
          <datalist id="list-district"><option ng-repeat="d in districts" value="{{d}}"/></datalist>
          <datalist id="list-landcover"><option ng-repeat="l in landcovers" value="{{l}}"/></datalist>
        </table>
        <button class="btn btn-info btn-xs" ng-show="o.data_info != undefined"
                data-toggle="popover" data-trigger="focus" data-html="true" data-placement="bottom"
                title="Data info" data-content="{{printDataInfo(o)}}">Data info</button>
        <button class="btn btn-info btn-xs" ng-show="o.validation != undefined"
                data-toggle="popover" data-trigger="focus" data-html="true" data-placement="bottom"
                title="Validation info" data-content="{{printValidationInfo(o)}}">Validation info</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button ng-if="o.data_info != undefined" ng-click="delete(o)" type="button" class="btn btn-danger">Delete</button>
        <button ng-click="saveOld(o)" type="button" class="btn btn-primary">Save changes</button>
        <button ng-if="!o.validation.validated" ng-click="validate(o)" type="button" class="btn btn-success">Validate</button>
        <button ng-if="o.validation.validated" ng-click="invalidate(o)" type="button" class="btn btn-default">Invalidate</button>
        <button ng-if="!o.validation.validated" ng-click="saveValidate(o)" type="button" class="btn btn-success">Save &amp; validate</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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
    ];
    $init->findings = \SSBIN\Finding::allWhere("ORDER BY id DESC LIMIT 50", []);
    $init->totalItems = \SSBIN\Finding::count();
    $init->paths = [
      'pic'=> \SSBIN\Finding::PICPATH,
      'thumb'=> \SSBIN\Finding::THUMBPATH,
      'icon'=> \SSBIN\Finding::ICONPATH
    ];
    echo json_encode($init);
    ?></div>
  <?php
}
