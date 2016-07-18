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
    <style>
      .navbar-default {background-image:linear-gradient(to bottom,#ffd 0,#ffa 100%)}
      .sidebar-nav ul li a.active,
      .nav>li>a:hover, .nav>li>a:focus,
      .nav .open>a:hover, .nav .open>a:focus, .nav .open>a:active { background-color: #ff8;}
      .nav .open>a { background-color: transparent;}
    </style>
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

function dd_menus() { global $login; ?>
  <li class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
      <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
    </a>
    <ul class="dropdown-menu dropdown-user">
      <li><a href="/users"><i class="fa fa-fw fa-home"></i> User Home</a></li>
      <li><a href="/users/profile"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
      <li><a href="/users/password"><i class="fa fa-gear fa-fw"></i> Change Password</a></li>
      <li class="divider"></li>
      <li><a href="/users/findings"><i class="fa fa-fw fa-database"></i> Input Data</a></li>
      <li class="divider"></li>
      <li><a href="/expert/input"><i class="fa fa-fw fa-edit"></i> Data Validation</a></li>
      <li><a href="/expert/users"><i class="fa fa-fw fa-users"></i> User Validation</a></li>
      <li class="divider"></li>
      <?php if ($login->level == \SSBIN\User::USER_ADMIN) { ?>
      <li><a href="/admin"><i class="fa fa-fw fa-gears"></i> Menu Admin</a></li>
      <li class="divider"></li>
      <?php } ?>
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
  <li><a href="/expert"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a></li>
  <li><a href="/expert/users"><i class="fa fa-users fa-fw"></i> Users</a></li>
  <li><a href="/expert/findings"><i class="fa fa-file-text-o fa-fw"></i> Findings Validation</a></li>
  <li>
    <a href="#"><i class="fa fa-table fa-fw"></i> Taxonomies <span class="fa arrow"></span></a>
    <ul class="nav nav-second-level">
      <li><a href="/expert/taxonomies/classes"><i class="fa fa-th-list fa-fw"></i> Classes</a></li>
      <li><a href="/expert/taxonomies/families"><i class="fa fa-th-list fa-fw"></i> Families</a></li>
      <li><a href="/expert/taxonomies/genus"><i class="fa fa-th-list fa-fw"></i> Genus</a></li>
      <li><a href="/expert/taxonomies/species"><i class="fa fa-th-list fa-fw"></i> Species</a></li>
    </ul>
  </li>
<?php }
