<?php
$pageTitle = "Visitors";
$pageSubTitle = "View page requests";
include $template;

function htmlHead() { ?>
<script>var token='<?= \Trust\Server::csrf_token(); ?>';</script>
<script src="/jslib/moment.min.js"></script>
<script src="/js/admin/visitors.js"></script>
<script src="/jslib/mine/DirPaging2.js"></script>
<style> ul.pager li { cursor: pointer;} ul.pager li.active a { background:#00E; color:#FFF; border-color: #888; } </style>
<?php }


function mainContent() { ?>
<div class="row" ng-controller="ctrlVisit">
  <div class="form-inline form-group-sm" style="position: relative;">
    From : <input type="text" placeholder="yyyy-MM-dd HH:mm:ss" ng-model="search.from" class="form-control"/>
    To: <input type="text" placeholder="yyyy-MM-dd HH:mm:ss" ng-model="search.to" class="form-control"/>
    <button class="btn btn-default btn-sm" data-toggle="modal" data-target="#modalAdvSearch"><i class="fa fa-plus fa-fw"></i></button>
    <button ng-click="view()" class="btn btn-default btn-sm">View <i class="fa fa-search fa-fw"></i></button>
  </div>
  <div ng-show="logs.length == 0">
    <h2>No data found</h2>
  </div>
  <div ng-show="logs.length > 0">
    <ul class="pager">
      <li class='previous'><a ng-click="changePage(1)" aria-label="First"><span aria-hidden="true"><i class="fa fa-angle-double-left fa-fw"></i></span></a></li>
      <li class='previous'><a ng-click="changePage(pager.currentPage-1)" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-angle-left fa-fw"></i></span></a></li>
      <li ng-repeat="p in pager.pages" ng-class="{active: pager.currentPage==p}" >
        <a ng-click="changePage(p)">{{p}}</a>
      </li>
      <li class='next'><a ng-click="changePage(pager.totalPages)" aria-label="Last"><span aria-hidden="true"><i class="fa fa-angle-double-right fa-fw"></i></span></a></li>
      <li class='next'><a ng-click="changePage(pager.currentPage+1)" aria-label="Next"><span aria-hidden="true"><i class="fa fa-angle-right fa-fw"></i></span></a></li>
    </ul>
    <p>{{pager.totalItems}} visits found, showing {{pager.startRecord}} to {{pager.endRecord}} of {{pager.totalItems}}</p>

    <table class="table table-responsive table-bordered table-striped table-condensed">
      <thead>
        <tr>
          <th>No</th>
          <th>Time</th>
          <th>Requested URL</th>
          <th>IP Address</th>
          <th>User id</th>
          <th>Post Data</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="l in logs | startFrom:pager.startRecord-1 | limitTo: pager.itemsPerPage">
          <td>{{$index + pager.startRecord}}</td>
          <td>{{momenter(l.time)}}</td>
          <td>{{l.requested_url}}</td>
          <td>{{l.ip_address}}</td>
          <td>{{l.userid}}</td>
          <td><button ng-click="showPost(l)" ng-if="l.post != null" class="btn btn-xs btn-info">Post Data</button></td>
        </tr>
      </tbody>
    </table>

    <p>{{pager.totalItems}} visits found, showing {{pager.startRecord}} to {{pager.endRecord}} of {{pager.totalItems}}</p>
    <ul class="pager">
      <li class='previous'><a ng-click="changePage(1)" aria-label="First"><span aria-hidden="true"><i class="fa fa-angle-double-left fa-fw"></i></span></a></li>
      <li class='previous'><a ng-click="changePage(pager.currentPage-1)" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-angle-left fa-fw"></i></span></a></li>
      <li ng-repeat="p in pager.pages" ng-class="{active: pager.currentPage==p}" >
        <a ng-click="changePage(p)">{{p}}</a>
      </li>
      <li class='next'><a ng-click="changePage(pager.totalPages)" aria-label="Last"><span aria-hidden="true"><i class="fa fa-angle-double-right fa-fw"></i></span></a></li>
      <li class='next'><a ng-click="changePage(pager.currentPage+1)" aria-label="Next"><span aria-hidden="true"><i class="fa fa-angle-right fa-fw"></i></span></a></li>
    </ul>
    <button class='btn btn-danger' data-toggle='modal' data-target='#modalDelete'><i class="fa fa-trash fa-fw"></i>Delete Logs...</button>
  </div>


<div id="modalAdvSearch" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body form-inline">
  <table>
    <tr>
      <td>From</td>
      <td>
        : <input type="text" placeholder="yyyy-MM-dd HH:mm:ss" ng-model="search.from" class="form-control"/>
        To: <input type="text" placeholder="yyyy-MM-dd HH:mm:ss" ng-model="search.to" class="form-control"/>
      </td>
    </tr>
    <tr><td>Type</td><td>: 
        <select ng-model="search.type" ng-options="a for a in types" class="form-control"></select></td></tr>
    <tr><td>IP</td><td>: <input type="text" ng-model="search.ip" class="form-control"/></td></tr>
    <tr><td>URL</td><td>: <input type="text" ng-model="search.url" class="form-control"/></td></tr>
    <tr><td>Action</td><td>: <input type="text" ng-model="search.a" class="form-control"/></td></tr>
  </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button ng-click="view()" type="button" class="btn btn-primary">View <i class="fa fa-search"></i></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
  

<div id="modalDelete" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete Logs</h4>
      </div>
      <div class="modal-body bg-warning">
        <div class="form-group">
          <label>Older than:</label>
          <input type="text" placeholder="yyyy-MM-dd HH:mm:ss" ng-model="delDate" class="form-control"/>
        </div>
      </div>
      <div class="modal-footer bg-danger">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button ng-click="del()" type="button" class="btn btn-danger">Delete <i class="fa fa-remove"></i></button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

  
  
<div id="modalShowPostData" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Post Data</h4>
      </div>
      <div class="modal-body">
        <div ng-repeat="(key, value) in log">
          <label class="control-label">{{key}}</label>
          <pre>{{stringify(value)}}</pre>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


</div>

<div id="init" style="display:none"><?php
$init = new stdClass();
//Changing this, need to also change search.from and search.to
$today = strtotime('00:00:00');
$yesterday = strtotime('-1 day',$today);
$yesterdayNight = $today-1;
$strWhere = ' WHERE time BETWEEN \''.\Trust\Date::toSqlDateTime($yesterday).'\' AND \''. \Trust\Date::toSqlDateTime($yesterdayNight).'\' ORDER BY time DESC';

$init->logs = \SSBIN\Logger::allWhere($strWhere,[]);
$init->totalItems = \Trust\DB::getOneVal("SELECT COUNT(*) FROM access_logs");
$init->types = TYPES;
echo json_encode($init);
?></div>
<?php }
