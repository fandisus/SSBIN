<!DOCTYPE html>
<html lang="en" ng-app="solaris">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= (isset($GLOBALS['pageTitle'])) ? APPNAME."-".$GLOBALS['pageTitle'] : APPNAME  ?></title>

    <meta charset="utf-8" />
    <meta name="description" content="Jaringan toko online"/>
    <meta name="author" content="trust1st" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0"/>
    <link rel="stylesheet" href="/css/s.php/gaya.scss" />
    <!--jQuery and Angular-->
    <script src="/jslib/jquery-2.2.2.min.js"></script>
    <script src="/jslib/angular-1.4.6/angular.min.js"></script>
    <!--bootstrap-->
    <link rel="stylesheet" href="/jslib/bootstrap-3.3.4/css/bootstrap.min.css"/>
    <script type="text/javascript" src="/jslib/bootstrap-3.3.4/js/bootstrap.min.js"></script>
    <!-- font awesome-->
    <link rel="stylesheet" href="/jslib/font-awesome-4.4.0/css/font-awesome.min.css" />
    <!--notifyjs-->
    <script src="/jslib/notify.min.js"></script>
    <!-- myloading.js -->
    <script type="text/javascript" src="/jslib/mine/myloading.js"></script>
    <script>
      var app = angular.module('solaris', []);
    </script>
    <?php if (function_exists("htmlHead")) htmlHead(); ?>
    <!-- Bootstrap Core CSS -->
    <!-- Custom CSS -->
    <link href="/jslib/sb-admin.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body><?php include DIR . "/applib/thirdParties.php";
    initFBML(); ?>
    <div id="wrapper">
      <!-- Navigation -->
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">So-Laris</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
          <li>
            <p class="navbar-text"><i class="fa fa-money"></i> <?= \Trust\Basic::RpShort($login->saldo) ?></p>
          </li>
          <?php
          //inboxMenu();
          //alertMenu();
          userMenu();
          sideMenu();
          ?>
        </ul>
      </nav>

      <div id="page-wrapper">
        <div class="container-fluid">
          <!-- Page Heading -->
          <?php if (isset($GLOBALS['pageTitle'])) { ?>
          <div class="row">
            <div class="col-lg-12">
              <h1 class="page-header">
                <?= $GLOBALS['pageTitle'] ?>
              </h1>
            </div>
          </div>
          <?php  } ?>
<?php if (function_exists("mainContent")) mainContent(); ?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

  </body>

</html>


<?php

function inboxMenu() { ?>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> <b class="caret"></b></a>
    <ul class="dropdown-menu message-dropdown">
      <li class="message-preview">
        <a href="#">
          <div class="media">
            <span class="pull-left">
              <img class="media-object" src="http://placehold.it/50x50" alt="">
            </span>
            <div class="media-body">
              <h5 class="media-heading"><strong>John Smith</strong>
              </h5>
              <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
              <p>Lorem ipsum dolor sit amet, consectetur...</p>
            </div>
          </div>
        </a>
      </li>
      <li class="message-preview">
        <a href="#">
          <div class="media">
            <span class="pull-left">
              <img class="media-object" src="http://placehold.it/50x50" alt="">
            </span>
            <div class="media-body">
              <h5 class="media-heading"><strong>John Smith</strong>
              </h5>
              <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
              <p>Lorem ipsum dolor sit amet, consectetur...</p>
            </div>
          </div>
        </a>
      </li>
      <li class="message-preview">
        <a href="#">
          <div class="media">
            <span class="pull-left">
              <img class="media-object" src="http://placehold.it/50x50" alt="">
            </span>
            <div class="media-body">
              <h5 class="media-heading"><strong>John Smith</strong>
              </h5>
              <p class="small text-muted"><i class="fa fa-clock-o"></i> Yesterday at 4:32 PM</p>
              <p>Lorem ipsum dolor sit amet, consectetur...</p>
            </div>
          </div>
        </a>
      </li>
      <li class="message-footer">
        <a href="#">Read All New Messages</a>
      </li>
    </ul>
  </li>
  <?php
}

function alertMenu() {
  ?>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>
    <ul class="dropdown-menu alert-dropdown">
      <li>
        <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
      </li>
      <li>
        <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
      </li>
      <li>
        <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
      </li>
      <li>
        <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
      </li>
      <li>
        <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
      </li>
      <li>
        <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
      </li>
      <li class="divider"></li>
      <li>
        <a href="#">View All</a>
      </li>
    </ul>
  </li>
  <?php
}

function userMenu() {
  global $login;
  ?>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
      <img class="img-circle" src="<?= $login->imageIcon(); ?>" style='width: 20px; height:20px;'/>
  <?= $login->kontak->nama ?><b class="caret"></b>
    </a>
    <ul class="dropdown-menu">
      <li><a href="/users"><i class="fa fa-fw fa-home"></i> Home</a></li>
      <li><a href="/users/profil"><i class="fa fa-fw fa-user"></i> Profil</a></li>
      <li><a href="/users/password"><i class="fa fa-fw fa-unlock-alt"></i> Password/User</a></li>
      <li><a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a></li>
  <!--      <li><a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a></li>-->
      <li class="divider"></li>
      <li><a href="/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
    </ul>
  </li>
  </ul>
  <?php
}

function sideMenu() { global $login;
  ?>
  <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav">
      <li><a href="/users"><i class="fa fa-fw fa-dashboard"></i> Home</a></li>
      <li><a href="/users/toko"><i class="fa fa-fw fa-building"></i> Manage toko</a></li>
      <li><a href="/users/history"><i class="fa fa-fw fa-history"></i> History belanja</a></li>
      <li><a href="/users/wishlist"><i class="fa fa-fw fa-list"></i> Wishlist</a></li>
      <li><a href="/users/saldo"><i class="fa fa-fw fa-money"></i> Riwayat Saldo</a></li>
      <li><a href="/users/payment"><i class="fa fa-fw fa-shopping-cart"></i> Top Up Saldo</a></li>
      <li><a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-building"></i> Toko <i class="fa fa-fw fa-caret-down"></i></a>
        <ul id="demo" class="collapse">
          <?php foreach ($login->tokos() as $t) { $t->writeHostsFile(); ?>
          <li><a href="https://<?= $t->homeUrl() ?>"><?= $t->homeUrl() ?></a></li>
          <?php } ?>
        </ul>
      </li>
    </ul>
  </div>
  <!-- /.navbar-collapse -->
<?php
}
