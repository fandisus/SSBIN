<!DOCTYPE html>
<html lang="en" ng-app="<?= APPNAME ?>">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= (isset($GLOBALS['pageTitle'])) ? APPNAME . "-" . $GLOBALS['pageTitle'] : APPNAME ?></title>

    <!-- jQuery & Angular -->
    <script src="/jslib/jquery-2.2.2.min.js"></script>
    <script src="/jslib/angular-1.4.6/angular.min.js"></script>

    <!-- Bootstrap Core -->
    <script src="/jslib/bootstrap-3.3.4/js/bootstrap.min.js"></script>
    <link href="/jslib/bootstrap-3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/jslib/bootstrap-3.3.4/css/bootstrap-theme.min.css"/>

    <!-- Metis Menu -->
    <script src="/jslib/metisMenu/metisMenu.min.js"></script>
    <link href="/jslib/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- sbadmin -->
    <script src="/jslib/sb-admin-2.js"></script>
    <link href="/jslib/sb-admin-2.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="/jslib/timeline.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="/jslib/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- notify.js -->
    <script src="/jslib/notify.min.js"></script>
    <!-- myloading.js -->
    <script src="/jslib/mine/myloading.js"></script>
    <script>var app = angular.module('<?= APPNAME ?>', []);</script>
    <?php if (function_exists("htmlHead")) htmlHead(); ?>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>

    <div id="wrapper">

      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/"><?= APPNAME ?></a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
          <?php dd_menus();// dd_messages(); dd_tasks(); dd_alerts(); ?>
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
          <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
              <?php //sidebar_search(); ?>
              <?php side_menus(); ?>
            </ul>
          </div>
          <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
      </nav>

      <div id="page-wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header"><?= $pageTitle ?></h1>
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <?php if (function_exists("mainContent")) mainContent(); ?>
      </div>
      <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
  </body>

</html>
<?php

function dd_messages() { ?>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-messages">
      <li>
        <a href="#">
          <div>
            <strong>John Smith</strong>
            <span class="pull-right text-muted">
              <em>Yesterday</em>
            </span>
          </div>
          <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <strong>John Smith</strong>
            <span class="pull-right text-muted">
              <em>Yesterday</em>
            </span>
          </div>
          <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <strong>John Smith</strong>
            <span class="pull-right text-muted">
              <em>Yesterday</em>
            </span>
          </div>
          <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a class="text-center" href="#">
          <strong>Read All Messages</strong>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
    </ul>
    <!-- /.dropdown-messages -->
  </li>
<?php }

function dd_tasks() { ?>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-tasks fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-tasks">
      <li>
        <a href="#">
          <div>
            <p>
              <strong>Task 1</strong>
              <span class="pull-right text-muted">40% Complete</span>
            </p>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                <span class="sr-only">40% Complete (success)</span>
              </div>
            </div>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <p>
              <strong>Task 2</strong>
              <span class="pull-right text-muted">20% Complete</span>
            </p>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                <span class="sr-only">20% Complete</span>
              </div>
            </div>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <p>
              <strong>Task 3</strong>
              <span class="pull-right text-muted">60% Complete</span>
            </p>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                <span class="sr-only">60% Complete (warning)</span>
              </div>
            </div>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <p>
              <strong>Task 4</strong>
              <span class="pull-right text-muted">80% Complete</span>
            </p>
            <div class="progress progress-striped active">
              <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                <span class="sr-only">80% Complete (danger)</span>
              </div>
            </div>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a class="text-center" href="#">
          <strong>See All Tasks</strong>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
    </ul>
    <!-- /.dropdown-tasks -->
  </li>
<?php }

function dd_alerts() { ?>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-alerts">
      <li>
        <a href="#">
          <div>
            <i class="fa fa-comment fa-fw"></i> New Comment
            <span class="pull-right text-muted small">4 minutes ago</span>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
            <span class="pull-right text-muted small">12 minutes ago</span>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <i class="fa fa-envelope fa-fw"></i> Message Sent
            <span class="pull-right text-muted small">4 minutes ago</span>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <i class="fa fa-tasks fa-fw"></i> New Task
            <span class="pull-right text-muted small">4 minutes ago</span>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">
          <div>
            <i class="fa fa-upload fa-fw"></i> Server Rebooted
            <span class="pull-right text-muted small">4 minutes ago</span>
          </div>
        </a>
      </li>
      <li class="divider"></li>
      <li>
        <a class="text-center" href="#">
          <strong>See All Alerts</strong>
          <i class="fa fa-angle-right"></i>
        </a>
      </li>
    </ul>
    <!-- /.dropdown-alerts -->
  </li>
<?php }

function dd_menus() { ?>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
      <li><a href="/users"><i class="fa fa-fw fa-home"></i> User Home</a></li>
      <li><a href="/users/profile"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
      <li><a href="/users/password"><i class="fa fa-gear fa-fw"></i> Change Password</a></li>
      <li class="divider"></li>
      <li><a href="/users/inputdata"><i class="fa fa-fw fa-database"></i> Input Data</a></li>
      <li class="divider"></li>
      <li><a href="/expert/input"><i class="fa fa-fw fa-edit"></i> Data Validation</a></li>
      <li><a href="/expert/users"><i class="fa fa-fw fa-users"></i> User Validation</a></li>
      <li class="divider"></li>
      <li><a href="/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
      </li>
    </ul>
    <!-- /.dropdown-user -->
  </li>
<?php }

function sidebar_search() { ?>
  <li class="sidebar-search">
    <div class="input-group custom-search-form">
      <input type="text" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">
          <i class="fa fa-search"></i>
        </button>
      </span>
    </div>
    <!-- /input-group -->
  </li>
<?php }


function side_menus() { ?>
  <li><a href="/admin"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
  <li><a href="/admin/users"><i class="fa fa-users fa-fw"></i> Users</a></li>
  <li><a href="/admin/organizations"><i class="fa fa-users fa-fw"></i> Categories / Organizations</a></li>
  <li>
    <a href="#"><i class="fa fa-table fa-fw"></i> Taxonomies <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
      <li><a href="/admin/taxonomies/classes"><i class="fa fa-th-list fa-fw"></i> Classes</a></li>
      <li><a href="/admin/taxonomies/families"><i class="fa fa-th-list fa-fw"></i> Families</a></li>
      <li><a href="/admin/taxonomies/genus"><i class="fa fa-th-list fa-fw"></i> Genus</a></li>
      <li><a href="/admin/taxonomies/species"><i class="fa fa-th-list fa-fw"></i> Species</a></li>
    </ul>
  </li>
  <li>
    <a href="#"><i class="fa fa-table fa-fw"></i> Lookup Values <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
      <li><a href="/admin/lookup/iucn"><i class="fa fa-globe fa-fw"></i> IUCN Status</a></li>
      <li><a href="/admin/lookup/indo"><i class="fa fa-flag-o fa-fw"></i> Indonesia Status</a></li>
      <li><a href="/admin/lookup/landcover"><i class="fa fa-map-o fa-fw"></i> Landcover</a></li>
      <li><a href="/admin/lookup/location"><i class="fa fa-map-marker fa-fw"></i> Location</a></li>
      <li><a href="/admin/lookup/grid"><i class="fa fa-map-pin fa-fw"></i> Grids</a></li>
    </ul>
  </li>
  <li>
    <a href="#"><i class="fa fa-database fa-fw"></i> Database <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
      <li><a href="/admin/database/backup"><i class="fa fa-download fa-fw"></i> Backup</li></a>
      <li><a href="/admin/database/restore"><i class="fa fa-upload fa-fw"></i> Restore</li></a>
      <li><a href="/admin/database/iexcel"><i class="fa fa-file-excel-o fa-fw"></i> Import Excel</li></a>
      <li><a href="/admin/database/eexcel"><i class="fa fa-upload fa-fw"></i> Export Excel</li></a>
    </ul>
  </li>
  <li><a href="/admin/visitors"><i class="fa fa-bar-chart-o fa-fw"></i> Visitors Log</a></li>
<?php }
