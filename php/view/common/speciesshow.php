<?php
$pageTitle = "Species Details";
include DIR.'/php/view/tulang.php';

function htmlHead() {
  ?>
  <link rel="stylesheet" href="/jslib/viewerjs/viewer.min.css"/>
  <script src="/jslib/viewerjs/viewer.min.js"></script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/js/common.js"></script>
  <script>
    $(document).ready(function() {
      window.Viewer;
      viewer = new Viewer(document.getElementById('images'), {url:'data-original'});
      $('[data-toggle="popover"]').popover();
    });
  </script>
  <style>
    ul#images { list-style: none; list-style-position: inside; margin-left: 0; padding-left: 0; text-align: center; }
    ul#images li { display: inline-block;}
    h2 { text-align: center; }
    tr > td:first-child { font-weight: bold; }
  </style>
<?php }

use SSBIN\Finding;
use Trust\Geo;

function mainContent() {  
  if (!isset($_GET['id']) || !is_numeric($_GET['id'])) { echo "<h3>Findings Data with id $_GET[id] was not found</h3>"; return; };
  $id = $_GET['id'];
  $f = Finding::find($id,Finding::PUBLICFIELDS);
  if ($f == null) {echo "<h3>Findings Data with id $_GET[id] was not found</h3>"; return;}
  ?>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h2>Findings Details: #<?= $f->id ?></h2>
      <div>
        <ul id="images">
          <?php foreach ($f->pic as $p) { ?>
          <li><img data-original="<?= Finding::PICPATH.$p ?>" src="<?= Finding::THUMBPATH.$p ?>"/></li>
          <?php } ?>
        </ul>
      </div>
      
      <table class="table table-condensed table-striped form-group form-group-sm">
        <tr>
          <td>ID</td>
          <td><?= $f->id ?></td>
        </tr>
        <tr>
          <td>Class</td>
          <td><?= $f->taxonomy->class ?></td>
        </tr>
        <tr>
          <td>Local Name</td>
          <td><?= $f->localname ?></td>
        </tr>
        <tr>
          <td>Other Name *</td>
          <td><?= $f->othername ?></td>
        </tr>
        <tr>
          <td>N *</td>
          <td><?= $f->n ?></td>
        </tr>
        <tr>
          <td>Family</td>
          <td><?= $f->taxonomy->family ?></td>
        </tr>
        <tr>
          <td>Genus</td>
          <td><?= $f->taxonomy->genus ?></td>
        </tr>
        <tr>
          <td>Species</td>
          <td><?= $f->taxonomy->species ?></td>
        </tr>
        <tr>
          <td>Common Name</td>
          <td><?= $f->commonname ?></td>
        </tr>
        <tr>
          <td>Survey Month</td>
          <td><?= date('F',strtotime($f->survey_date)) ?></td>
        </tr>
        <tr>
          <td>Survey Year</td>
          <td><?= date('Y',strtotime($f->survey_date)) ?></td>
        </tr>
        <tr>
          <td>District</td>
          <td><?= $f->district ?></td>
        </tr>
        <tr>
          <td>Landcover</td>
          <td><?= $f->landcover ?></td>
        </tr>
        <tr>
          <td>IUCN Status</td>
          <td><?= $f->iucn_status ?></td>
        </tr>
        <tr>
          <td>CITES Status</td>
          <td><?= $f->cites_status ?></td>
        </tr>
        <tr>
          <td>Indonesia Status</td>
          <td><?= $f->indo_status ?></td>
        </tr>
        <tr>
          <td>Data Source</td>
          <td><?= $f->data_source ?></td>
        </tr>
        <tr>
          <td>Reference</td>
          <td><?= $f->reference ?></td>
        </tr>
        <tr>
          <td>Other Information</td>
          <td><?= $f->other_info ?></td>
        </tr>
      </table>
    </div>
  </div>
<?php }
