<?php
$pageTitle = "Users";
$pageSubTitle = "Manage admins and experts";
include $template;

function htmlHead() { ?>
  <script>var token = '<?= \Trust\Server::csrf_token(); ?>';</script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
  <script src="/js/admin/users.js"></script>
  <style>
.rem-button {
  color:#F00; cursor: pointer;
}
  </style>
<?php }

function mainContent() { ?>
  <div class="row" ng-controller="ctrlUsers">
    <table class="table table-bordered table-striped table-condensed table-responsive">
      <thead>
        <tr>
          <th>Username</th>
          <th>Full Name</th>
          <th>Category</th>
          <th>Organization</th>
          <th>Level</th>
          <th>Expertise</th>
          <th>Validated</th>
        </tr>
      </thead>
      <tbody>
        <tr ng-repeat="u in users">
          <td><a ng-click="show(u)">{{u.username}}</a></td>
          <td>{{u.biodata.name}}</td>
          <td><a ng-click="edit(u)">{{u.category}}</a></td>
          <td><a ng-click="edit(u)">{{u.organization}}</a></td>
          <td><a ng-click="edit(u)">{{u.level}}</a></td>
          <td><a ng-click="editExpertise(u)">
              <div ng-if="u.expertise.length == 0">-</div>
              <div ng-repeat="e in u.expertise">{{e}}</div>
          </a></td>
          <td>
            <button ng-click="toggleValidation(u)" ng-if="u.validated" class="btn btn-success btn-xs">{{u.validated}}</button>
            <button ng-click="toggleValidation(u)" ng-if="!u.validated" class="btn btn-danger btn-xs">{{u.validated}}</button>
          </td>
        </tr>
      </tbody>
    </table>
    
<div id="modalShowUser" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">User: {{u.username}}, Level:{{u.level}}</h4>
      </div>
      <div class="modal-body">
        <h3>{{u.username}} ({{u.level}} user)</h3>
        <table>
          <tr><td>date joined </td><td> : {{momenter(u.login_info.join_date)}}</td></tr>
          <tr><td>last login </td><td> : {{momenter(u.login_info.last_login)}}</td></tr>
        </table>
        <table class="table table-condensed table-striped">
            <tr>
              <td>Status</td>
              <td>
                <span ng-if="u.validated" class="label label-success">Validated</span>
                <span ng-if="!u.validated" class="label label-danger">Not Validated</span>
                <span ng-if="u.active" class="label label-success">Activated</span>
                <span ng-if="!u.active" class="label label-danger">Not Activated</span>
              </td>
            </tr>
            <tr><td>Name</td><td>{{u.biodata.name}}</td></tr>
            <tr><td>Category</td><td>{{u.category}}</td></tr>
            <tr><td>Organization</td><td>{{u.organization}}</td></tr>
            <tr><td>E-mail</td><td>{{u.biodata.email}}</td></tr>
            <tr><td>Gender</td><td>{{u.biodata.gender}}</td></tr>
            <tr><td>DOB</td><td>{{u.biodata.dob}}</td></tr>
            <tr><td>City</td><td>{{u.biodata.city}}</td></tr>
            <tr><td>Phone(s)</td><td style="white-space: pre-line">{{phoneLists(u)}}</td></tr>
            <tr>
              <td>Expertise</td>
              <td>
                <div ng-if="u.expertise.length == 0">-</div>
                <div ng-repeat="e in u.expertise">{{e}}</div>
              </td>
            </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<div id="modalExpertise" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Expertise for: {{editUser.username}} </h4>
      </div>
      <div class="modal-body">
        <div class="form-inline">
          <div class="form-group">
            <label>Class</label>
            <select class="form-control" ng-model="class" ng-options="c.class as c.class for c in classes"></select>
            <button ng-click="addClass(class)" class="btn btn-success"><i class="fa fa-plus"></i></button>
          </div>
        </div>
        <table>
          <tr ng-repeat="e in editUser.expertise">
            <td>{{e}}</td><td><a ng-click="remExpertise(e)" class="rem-button"><i class="fa fa-remove fa-fw"></i></a></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button ng-click="saveExpertise(editUser)" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit User: {{editUser.username}}</h4>
      </div>
      <div class="modal-body">
        <table>
          <tr>
            <td>Category</td>
            <td><select ng-model="editUser.category" class="form-control"
                        ng-options="key as key for (key,value) in categories" ng-change="updateOrganizationSelect()"></select>
            </td>
          </tr>
          <tr>
            <td>Organization</td>
            <td><select ng-model="editUser.organization" class="form-control"
                        ng-options="o.name as o.name for o in organizations"></select></td>
          </tr>
          <tr>
            <td>Level</td>
            <td><select ng-model="editUser.level" class="form-control"
                        ng-options="l as l for l in levels"></select></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button ng-click="saveChanges(editUser)" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

  
  <div id="init" style="display:none;"><?php
    $init = new stdClass();
    $init->users = \SSBIN\User::allWhere(" ORDER BY category, organization",[]);
    foreach ($init->users as $k=>$u)
      unset(
            $init->users[$k]->login_sys,
            $init->users[$k]->password
            );
    $init->categories = \SSBIN\Organization::getNestedArray();
    $init->levels = [\SSBIN\User::USER_ADMIN,  \SSBIN\User::USER_EXPERT, \SSBIN\User::USER_STANDARD];
    $init->classes = \SSBIN\Classes::allWhere("ORDER BY class ASC", []);
    echo json_encode($init);
  ?></div>
<?php
}
