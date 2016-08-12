<?php
include DIR."/php/view/tulang.php";
function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/js/common.js"></script>
  <script src="/jslib/mine/DirPaging2.js"></script>
  <script src="/js/common/indices.js"></script>
  <script>
    $(document).ready(function() {
      $("input, select").addClass('form-control');
      $('#filter-table tbody').children().css({display:'none'});
    });
  </script>
<?php }

function mainContent() { ?>
  <h2>Diversity Indices</h2>
  <div class="row" ng-controller="ctrlIndices">
    <div class="col-md-12">
      <div class="form-group form-inline">
        <h4 style="display: inline-block;">Filter by:</h4>
        <select ng-model="filter" ng-options="f.text for f in fields"></select>
        &nbsp;<button ng-click="addFilter(filter)" class="btn btn-success btn-sm round"><i class="fa fa-plus fa-fw"></i></button>
        &nbsp;<button ng-click="showIndices()" class="btn btn-success btn-sm round"><i class="fa fa-calculator fa-fw"></i></button>
      </div>
      <table class="form-group form-group-sm" id="filter-table">
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
        <tr id="surveydate">
          <td><button ng-click="removeFilter($event)" class="btn btn-danger btn-xs round"><i class="fa fa-remove fa-fw"></i></button></td>
          <td>Survey Date </td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.startDate" /></td>
          <td>To</td><td><input type="date" placeholder="yyyy-mm-dd" ng-model="params.endDate"/></td>
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
      </table>
      
      <div ng-show="rows.length > 0" class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-condensed">
          <thead>
            <tr>
              <th>Family</th>
              <th>Genus</th>
              <th>Species</th>
              <th>N</th>
              <th>Pi</th>
              <th>ln(Pi)</th>
              <th>Pi &times; ln(Pi)</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="r in rows">
              <td>{{r.family}}</td>
              <td>{{r.genus}}</td>
              <td>{{r.species}}</td>
              <td>{{r.n}}</td>
              <td>{{r.pi}}</td>
              <td>{{r.lnpi}}</td>
              <td>{{r.pilnpi}}</td>
            </tr>           
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">S={{summ.S}}</td>
              <td>Sum={{summ.sum}}</td>
              <td colspan="2"></td>
              <td>Sum={{summ.H}}</td>
            </tr>
          </tfoot>
        </table>
        <div>H = {{summ.H}}</div>
        <div>Hmax = ln(S) = ln({{summ.s}}) = {{summ.Hmax}}</div>
        <div>E = H/Hmax = {{summ.H}}/{{summ.Hmax}} = {{summ.E}}</div>
        <div>Simpson Index = {{summ.simpson}}</div>
      </div>
    </div>
  </div>

  <div id="init" style="display:none"><?php
    //class family district lancov iucn indo  genus species grids
    $init = new stdClass();
    $init->classes = \SSBIN\Classes::getList();
    $init->districts = \SSBIN\Location::getList();
    $init->landcovers = \SSBIN\Landcover::getList();
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
    echo json_encode($init);
    ?></div>
  <?php
}
