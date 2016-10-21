<?php
$pageTitle = "Network";
include DIR.'/php/view/tulang.php';

function htmlHead() { ?>
  <script>
    var token = '<?= \Trust\Server::csrf_token(); ?>';
    var icopath = '<?= \SSBIN\User::ICONPATH ?>';
    var picpath = '<?= \SSBIN\User::PICPATH ?>';
  </script>
  <script src="/jslib/moment.min.js"></script>
  <script src="/jslib/mine/commonjs.js"></script>
<?php }

function mainContent() { global $paths;
  $cat = $paths[1];
  if (isset($paths[2])) {
    $org = $paths[2];
    $description = \SSBIN\Organization::where('WHERE name=:org AND category=:cat', ['org'=>$org,'cat'=>$cat],'description')->description;
    //untuk di bagian data contribution
    $strWhere = 'WHERE organization=:org AND category=:cat'; 
    $colVals = ['org'=>$org,'cat'=>$cat];
  } else {
    $org = null;
    $description = null;
    //untuk di bagian data contribution
    $strWhere = 'WHERE category=:cat'; 
    $colVals = ['cat'=>$cat];
  }
?>

  <div class='row'>
    <h3>Category: <?= $cat; ?></h3>
    <?php if ($org != null) { ?>
    <h3>Organization: <?= $org; ?></h3>
    <p style='max-width: 450px;'><?=$description ?></p>
    <?php } ?>
    <h4>Data Contribution</h4>
    <?php
      $contribs = \Trust\DB::get("SELECT taxonomy->>'class' AS class, COUNT(*) FROM findings "
      . "WHERE data_info->>'created_by' IN "
      . "(SELECT username FROM users $strWhere) AND validation->>'validated'='true' GROUP BY taxonomy->>'class';", $colVals);
    if (!count($contribs)) { ?>
    <h5>No data found</h5>
    <?php } else { ?>
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-condensed" style='max-width: 300px;'>
        <thead>
          <th>Class</th>
          <th>Data Count</th>
        </thead>
        <tbody>
          <?php foreach ($contribs as $d) { ?>
          <tr>
            <td><?= $d->class ?></td>
            <td><?= $d->count ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
    <?php } ?>
  </div>
<?php
}
