<!doctype html>
<html lang="en" ng-app="ssbin">
  <head>
    <title><?php echo (isset($title)) ? $title : "SSBIN"; ?></title>
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
                <li><a href="/users/profil"><i class="fa fa-fw fa-user"></i> Profil</a></li>
                <li><a href="/users/password"><i class="fa fa-fw fa-unlock-alt"></i> Password/User</a></li>
                <li><a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a></li>
                <li class="divider"></li>
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
            <!-- Classic list -->
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" class="dropdown-toggle" aria-expanded="true">
                List<b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <!-- Content container to add padding -->
                  <div class="yamm-content">
                    <div class="row">
                      <ul class="col-sm-3 list-unstyled">
                        <li>
                          <p><strong>Section Title</strong></p>
                        </li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                      </ul>
                      <ul class="col-sm-3 list-unstyled">
                        <li>
                          <p><strong>Links Title</strong></p>
                        </li>
                        <li><a href="#"> Link Item </a></li>
                        <li><a href="#"> Link Item </a></li>
                        <li><a href="#"> Link Item </a></li>
                        <li><a href="#"> Link Item </a></li>
                        <li><a href="#"> Link Item </a></li>
                        <li><a href="#"> Link Item </a></li>
                      </ul>
                      <ul class="col-sm-3 list-unstyled">
                        <li>
                          <p><strong>Section Title</strong></p>
                        </li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>List Item</li>
                      </ul>
                      <ul class="col-sm-3 list-unstyled">
                        <li>
                          <p><strong>Section Title</strong></p>
                        </li>
                        <li>List Item</li>
                        <li>List Item</li>
                        <li>
                          <ul>
                            <li><a href="#"> Link Item </a></li>
                            <li><a href="#"> Link Item </a></li>
                            <li><a href="#"> Link Item </a></li>
                          </ul>
                        </li>
                      </ul>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Accordion demo -->
            <li class="dropdown">
              <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                Accordion<b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <div class="yamm-content">
                    <div class="row">
                      <div id="accordion" class="panel-group">
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Collapsible Group Item #1</a></h4>
                          </div>
                          <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">Ut consectetur ullamcorper purus a rutrum. Etiam dui nisi, hendrerit feugiat scelerisque et, cursus eu magna. </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Collapsible Group Item #2</a></h4>
                          </div>
                          <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">Nullam pretium fermentum sapien ut convallis. Suspendisse vehicula, magna non tristique tincidunt, massa nisi luctus tellus, vel laoreet sem lectus ut nibh. </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">Collapsible Group Item #3</a></h4>
                          </div>
                          <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">Praesent leo quam, faucibus at facilisis id, rhoncus sit amet metus. Sed vitae ipsum non nibh mattis congue eget id augue. </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
            <!-- Classic dropdown -->
            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Classic<b class="caret"></b></a>
              <ul role="menu" class="dropdown-menu">
                <li><a tabindex="-1" href="#"> Action </a></li>
                <li><a tabindex="-1" href="#"> Another action </a></li>
                <li><a tabindex="-1" href="#"> Something else here </a></li>
                <li class="divider"></li>
                <li><a tabindex="-1" href="#"> Separated link </a></li>
              </ul>
            </li>
            <!-- Pictures -->
            <li class="dropdown yamm-fw"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Pictures<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li>
                  <div class="yamm-content">
                    <div class="row">
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                      <div class="col-xs-6 col-sm-2"><a href="#" class="thumbnail"><img alt="150x190" src="/images/paw.png"></a></div>
                    </div>
                  </div>
                </li>
              </ul>
            </li>
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
