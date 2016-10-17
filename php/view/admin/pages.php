<?php
$pageTitle = "Pages";
$pageSubTitle = "Manage pages";
include $template;

function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/summernote/summernote.min.js"></script>
  <link rel="stylesheet" href="/jslib/summernote/summernote.css"/>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/js/common.js"></script>
  <script src="/js/admin/pages.js"></script>
  <script>
    $(document).ready(function() {
      $('#summernote').summernote({height:300});
    });
  </script>
<?php }

function mainContent() { ?>
  <div class="row" ng-controller="ctrlPages">
    <div class="col-md-6">
      <button ng-click="newO()" class="btn btn-success">New <i class="fa fa-plus fa-fw"></i></button>
      <br /><br />
      <table class="table table-bordered table-striped table-condensed table-responsive">
        <thead>
          <tr>
            <th></th>
            <th>Name</th>
            <th>Position</th>
            <th>group_name</th>
            <th>order_no</th>
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="o in pages">
            <td>{{$index + 1}}</td>
            <td><a ng-click="edit(o)">{{o.name}}</a></td>
            <td><a ng-click="edit(o)">{{o.position}}</a></td>
            <td><a ng-click="edit(o)">{{o.group_name}}</a></td>
            <td><a ng-click="edit(o)">{{o.order_no}}</a></td>
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
                  <td>Name</td>
                  <td>
                    <input type="text" ng-model="o.name" class="form-control"/>
                  </td>
                </tr>
                <tr>
                  <td>Position</td>
                  <td>
                    <input type="text" ng-model="o.position" class="form-control" readonly="readonly" />
                  </td>
                </tr>
                <tr>
                  <td>Group name</td>
                  <td>
                    <input type="text" ng-model="o.group_name" class="form-control" />
                  </td>
                </tr>
                <tr>
                  <td>Order</td>
                  <td>
                    <input type="number" ng-model="o.order_no" class="form-control" />
                  </td>
                </tr>
                <tr>
                  <td>Content</td>
                  <td>
                    <div id="summernote">Halo</div>
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
    $init->pages = \SSBIN\Pages::allWhere("ORDER BY group_name, order_no", [], "id, name, position, group_name, order_no");
    echo json_encode($init);
  ?></div>
<?php
}
