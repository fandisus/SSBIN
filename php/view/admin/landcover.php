<?php
$pageTitle = "Landcovers";
$pageSubTitle = "Manage Landcovers";
include $template;

function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/js/common.js"></script>
  <script src="/js/admin/landcover.js"></script>
  <script src="/jslib/mine/DirPaging2.js"></script>
<?php }

function mainContent() { ?>
  <div class="row" ng-controller="ctrlLandcover">
    <div class="col-md-6">
      <tr-paging public="pager"></tr-paging>
      <button ng-click="newO()" class="btn btn-success">New <i class="fa fa-plus fa-fw"></i></button>
      <br /><br />
      <table class="table table-bordered table-striped table-condensed table-responsive">
        <thead>
          <tr>
            <th>No</th>
            <th>Landcover</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="o in landcovers">
            <td>{{$index + pager.startRecord()}}</td>
            <td><a ng-click="edit(o)">{{o.landcover}}</a></td>
          </tr>
        </tbody>
      </table>
    
      <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">{{modalTitle}}</h4>
            </div>
            <div class="modal-body">
              <table>
                <tr>
                  <td>Landcover</td>
                  <td>
                    <input type="text" ng-model="o.landcover" class="form-control" list="catList"/>
                    <datalist id="catList"><option ng-repeat="c in landcoverList" ng-change="oChanged()" value="{{c}}"></option></datalist>
                  </td>
                </tr>
              </table>
              <button class="btn btn-info btn-xs" ng-show="o.data_info != undefined"
                      data-toggle="popover" data-trigger="focus" data-html="true" data-placement="bottom"
                      title="Data info" data-content="{{printDataInfo(o)}}">Data info</button>
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
    $init = new stdClass();
    $init->landcovers = \SSBIN\Landcover::allWhere(" ORDER BY landcover LIMIT 50", []);
    $init->totalItems = \SSBIN\Landcover::count();
    echo json_encode($init);
  ?></div>
<?php
}
