<?php
$pageTitle = "Findings";
$pageSubTitle = "Input Findings";
include $template;

function htmlHead() {
  ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/js/common.js"></script>
  <script src="/js/user/findings.js"></script>
  <script src="/jslib/mine/DirPaging2.js"></script>
  <script src="/jslib/mine/DirDegdms.js"></script>
  <script>
    $(document).ready(function() {
      $("input, select").addClass('form-control');
    });
  </script>
  <style>
    td>input.form-control, td>select.form-control { width:100%;}
  </style>
<?php }

function mainContent() {
  ?>
  <div class="row" ng-controller="ctrlFindings">
    <div class="col-md-12">
      <tr-paging public="pager"></tr-paging>
      <button ng-click="newO()" class="btn btn-success">New <i class="fa fa-plus fa-fw"></i></button>
      <br /><br />
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-condensed table-hover">
          <thead>
            <tr>
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
            <tr ng-repeat="o in findings" ng-click="edit(o)">
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
                  <td>ID</td>
                  <td><input type="text" ng-model="o.id" readonly="readonly"/></td>
                </tr>
                <tr>
                  <td>Class *</td>
                  <td><input type="text" ng-model="o.taxonomy.class" list="list-class"/></td>
                </tr>
                <tr>
                  <td>Local Name *</td>
                  <td><input type="text" ng-model="o.localname"/></td>
                </tr>
                <tr>
                  <td>Other Name *</td>
                  <td><input type="text" ng-model="o.othername"/></td>
                </tr>
                <tr>
                  <td>N *</td>
                  <td><input type="text" ng-model="o.n"/></td>
                </tr>
                <tr>
                  <td>Family</td>
                  <td><input type="text" ng-model="o.taxonomy.family" list="list-family" ng-change="searchFamily(o)"/></td>
                </tr>
                <tr>
                  <td>Genus</td>
                  <td><input type="text" ng-model="o.taxonomy.genus" list="list-genus" ng-change="searchGenus(o)"/></td>
                </tr>
                <tr>
                  <td>Species</td>
                  <td><input type="text" ng-model="o.taxonomy.species" list="list-species" ng-change="searchSpecies(o)"/></td>
                </tr>
                <tr>
                  <td>Common Name</td>
                  <td><input type="text" ng-model="o.commonname"/></td>
                </tr>
                <tr>
                  <td>Survey Month</td>
                  <td><select ng-model="o.survey_month" ng-options="m.num as m.name for m in months" /></td>
                </tr>
                <tr>
                  <td>Survey Year</td>
                  <td><input type="text" ng-model="o.survey_year"/></td>
                </tr>
                <tr>
                  <td>Latitude</td>
                  <td><tr-degdms deg="o.latitude" public='latinput' type="lat" mode="dms"></tr-degdms></td>
                </tr>
                <tr>
                  <td>Longitude</td>
                  <td><tr-degdms deg="o.longitude" public='longinput' type="long" mode="dms"></tr-degdms></td>
                </tr>
                <tr>
                  <td>Grid</td>
                  <td><input type="text" ng-model="o.grid" list="list-grid" ng-change="searchGrid(o)"/></td>
                </tr>
                <tr>
                  <td>Village</td>
                  <td><input type="text" ng-model="o.village"/></td>
                </tr>
                <tr>
                  <td>District</td>
                  <td><input type="text" ng-model="o.district" list="list-district"/></td>
                </tr>
                <tr>
                  <td>Landcover</td>
                  <td><input type="text" ng-model="o.landcover" list="list-landcover"/></td>
                </tr>
                <tr>
                  <td>IUCN Status</td>
                  <td><select ng-model="o.iucn_status" ng-options="s.abbr as s.long_name for s in iucns"></select></td>
                </tr>
                <tr>
                  <td>CITES Status</td>
                  <td><select ng-model="o.cites_status" ng-options="s.abbr as s.long_name for s in indos"/></td>
                </tr>
                <tr>
                  <td>Indonesia Status</td>
                  <td><input type="text" ng-model="o.indo_status" list="list-indo"/></td>
                </tr>
                <tr>
                  <td>Data Source</td>
                  <td><input type="text" ng-model="o.data_source"/></td>
                </tr>
                <tr>
                  <td>Reference</td>
                  <td><input type="text" ng-model="o.reference"/></td>
                </tr>
                <tr>
                  <td>Other Information</td>
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
              <button ng-click="saveChanges(o)" type="button" class="btn btn-primary">Save changes</button>
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
    echo json_encode($init);
    ?></div>
  <?php
}
