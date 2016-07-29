<?php use SSBIN\User; ?><!doctype html>
<html lang="en" ng-app="ssbin">
  <head>
    <title><?= (isset($GLOBALS['pageTitle'])) ? APPNAME."-".$GLOBALS['pageTitle'] : APPNAME  ?></title>
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--<link rel="shortcut icon" href="../images/icfm.ico" type="image/x-icon"> -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <meta charset="utf-8" />
    <meta name="description" content="South Sumatera Biodiversity Information Network"/>
    <meta name="author" content="trust1st" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0"/>
    <!--jQuery and Angular-->
    <script src="/jslib/jquery-2.2.2.min.js"></script>
    <script src="/jslib/angular-1.4.6/angular.min.js"></script>
    <!--bootstrap-->
    <link rel="stylesheet" href="/jslib/bootstrap-3.3.4/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/jslib/bootstrap-3.3.4/css/bootstrap-theme.min.css"/>
    <script type="text/javascript" src="/jslib/bootstrap-3.3.4/js/bootstrap.min.js"></script>
    <!--yamm megamenu -->
    <link rel="stylesheet" href="/jslib/yamm.css"/>
    <script>
      $(document).on('click', '.yamm .dropdown-menu', function(e) { e.stopPropagation(); });
    </script>
    <!-- font awesome-->
    <link rel="stylesheet" href="/jslib/font-awesome-4.4.0/css/font-awesome.min.css" />
    <!--notifyjs-->
    <script src="/jslib/notify.min.js"></script>
    <!-- myloading.js -->
    <script type="text/javascript" src="/jslib/mine/myloading.js"></script>
    <link rel="stylesheet" href="/css/s.php/gaya.scss" />
    <script>
      var app=angular.module('ssbin',[]);
    </script>
    <style>
    </style>
    <?php if (function_exists("htmlHead")) htmlHead(); ?>
  </head>
  <body>
    <header>
      <div class="container">
        <div class="row flex flex-vcenter">
          <div class="col-lg-2 col-xs-12" id="logo">
            <a href="/"><span><?= APPNAME ?></span></a>
          </div>
          <div class="col-lg-6 col-xs-12" id="search">
            <form class="flex flex-vcenter">
              <div class="form-group" style="margin-bottom: 0;">
                <div class="input-group">
                  <input type="text" placeholder="Find Species" class="form-control"/>
                  <div class="input-group-btn">
                    <button class="btn btn-default">
                      <span class="glyphicon glyphicon-search"></span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="col-lg-4 col-xs-12" id="buttons">
            <?php if (!isset($_SESSION['login'])) { ?>
            <div class="btn-group"><!-- potongan kanan: login register -->
              <a href="/login" class="btn btn-default">Login</a>
              <a href="/register" class="btn btn-success">Register</a>
            </div>
            <?php } else { ?>
            <div class="dropdown"><!-- potongan kanan: logout -->
              <div class="dropdown-toggle" data-toggle="dropdown" style="cursor: pointer;">
                <img class="img-circle" src="<?= $login->imageIcon(); ?>" style='width: 30px; height:30px;'/>
                <?= $login->biodata->name ?>
              </div>
              <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="/users"><i class="fa fa-fw fa-home"></i> Home</a></li>
                <li><a href="/users/profile"><i class="fa fa-fw fa-user"></i> Profile</a></li>
                <li><a href="/users/password"><i class="fa fa-fw fa-unlock-alt"></i> Password</a></li>
                <li class="divider"></li>
                <?php if ($login->validated) { ?>
                <li><a href="/users/findings"><i class="fa fa-fw fa-database"></i> Input Data</a></li>
                <li class="divider"></li>
                <?php } ?>
                <?php if (in_array($login->level,[User::USER_EXPERT,User::USER_ADMIN])) { ?>
                <li><a href="/expert/input"><i class="fa fa-fw fa-edit"></i> Data Validation</a></li>
                <li><a href="/expert/users"><i class="fa fa-fw fa-users"></i> User Validation</a></li>
                <li class="divider"></li>
                <?php } ?>
                <?php if ($login->level == User::USER_ADMIN) { ?>
                <li><a href="/admin"><i class="fa fa-fw fa-gears"></i> Menu Admin</a></li>
                <li class="divider"></li>
                <?php } ?>
                <li><a href="/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a></li>
              </ul>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </header>
    
    
    <!--  Navigation  -->
    <div class="navbar yamm navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" data-toggle="collapse" data-target="#navbar-collapse-1" class="navbar-toggle">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div id="navbar-collapse-1" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="/"><i class="fa fa-home fa-fw"></i> Home</a></li>
            <!-- Classic list -->
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true">
                Network<b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <!-- Content container to add padding -->
                  <div class="yamm-content">
                    <div class="row media-body"><?php $cats = \SSBIN\Organization::getNestedArray();
                      foreach ($cats as $cat=>$orgArr) { ?>
                        <ul class="col-sm-2 list-unstyled">
                          <li>
                            <p><a href="/userslist/<?=$cat?>"><strong><?= $cat ?></strong></a></p>
                          </li>
                          <?php foreach ($orgArr as $org) { ?>
                          <li><a href="/userslist/<?= "$cat/$org->name" ?>"><?=$org->name?></a></li>
                          <?php } ?>
                        </ul>
                      <?php } ?>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            
            <!-- Classic dropdown -->
            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Species Distribution<b class="caret"></b></a>
              <ul role="menu" class="dropdown-menu">
                <li><a tabindex="-1" href="/specieslist"> Search</a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="/indices"> Diversity Indices </a></li>
              </ul>
            </li>
            <li><a href="/about">About SSBIN</a></li>
            <li><a href="/contactus">Contact us</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    
    <main>
      <div class="container">
        <?php if (function_exists("mainContent")) mainContent(); ?>
      </div>
    </main>
    <footer>
      <div class="container">
        <div class="row flex flex-vtop">
          <div class="col-lg-3 col-md-6 col-xs-12">
            <h3>How To</h3>
            <ul>
              <li><a href="#">Register</a></li>
              <li><a href="#">Input Data</a></li>
              <li><a href="#">Find Data</a></li>
              <li><a href="#">Request Data</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-xs-12">
            <h3>About Us</h3>
            <ul>
              <li><a href="#">SSBIN Project</a></li>
              <li><a href="#">Universitas Sriwijaya</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-md-6 col-xs-12">
            <h3>Terms and Usage</h3>
            <ul>
              <li><a href="#">Data Ownership</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Disclaimer</a></li>
            </ul>
          </div>
        </div>
        <div id="copyright">Copyright &copy; 2016 by Universitas Sriwijaya</div>
      </div>
    </footer>
  </body>
</html>
