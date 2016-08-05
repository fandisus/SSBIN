<?php
$pageTitle = "Organization";
$pageSubTitle = "Manage Categories and Organizations";
include $template;

function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/js/common.js"></script>
  <script src="/js/admin/organizations.js"></script>
<?php }

function mainContent() { ?>
  <div class="row" ng-controller="ctrlOrg">
    <div class="col-md-6">
      
      <button ng-click="newO()" class="btn btn-success">New <i class="fa fa-plus fa-fw"></i></button>
      <br /><br />
      <table class="table table-bordered table-striped table-condensed table-responsive">
        <thead>
          <tr>
            <th>Category</th>
            <th>Organization</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="o in organizations">
            <td><a ng-click="edit(o)">{{o.category}}</a></td>
            <td><a ng-click="edit(o)">{{o.name}}</a></td>
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
                  <td>Category</td>
                  <td>
                    <input type="text" ng-model="o.category" class="form-control" list="catList"/>
                    <datalist id="catList"><option ng-repeat="c in catList" value="{{c}}"></option></datalist>
                  </td>
                </tr>
                <tr>
                  <td>Organization</td>
                  <td>
                    <input type="text" ng-model="o.name" class="form-control" list="orgList"/>
                    <datalist id="orgList"><option ng-repeat="org in orgList" value="{{org}}"></option></datalist>
                  </td>
                </tr>
                <tr>
                  <td>Description</td>
                  <td>
                    <textarea ng-model="o.description" class="form-control"></textarea>
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
    $init->organizations = \SSBIN\Organization::allWhere(" ORDER BY category, name", []);
    $init->catList = [];
    $init->orgList = [];
    foreach ($init->organizations as $v) {
      if (!in_array($v->category, $init->catList)) $init->catList[] = $v->category;
      if (!in_array($v->name, $init->orgList)) $init->orgList[] = $v->name;
    }
    echo json_encode($init);
  ?></div>
<?php
}
