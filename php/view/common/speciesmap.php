<?php
$pageTitle = "Species Map";
include DIR.'/php/view/tulang.php';

function htmlHead() { ?>
  <script src="http://maps.google.com/maps/api/js"></script>
  <script src="/jslib/gmaps.js"></script>
  <style>
    #map, #panorama { height:300px; background:#6699cc; }
  </style>
<?php }

function mainContent() { global $login, $p, $message;
  $center=getCenter($p->findings);
  $init=[
    'res'=>summarize($p->findings),
    'lat'=>$center->latitude,
    'long'=>$center->longitude
  ]
  ?>
  <div class="row">
    <div class="col-md-12">
      <h2>Species map</h2>
      <h5><?= count($p->findings).' data found'?><?= ($center->errCount > 0) ? ', ('.$center->errCount.') has incomplete coordinates' : '' ?></h5>
      <div id="map"></div>
    </div>
  </div>
  <div id="init"><?php
  echo json_encode($init);
  ?></div>
  
<div id="modalDetails" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Species Findings</h4>
      </div>
      <div class="modal-body">
        <div id="details-panel"></div>
      </div>
    </div>
  </div>
</div>      

<script src="/js/common/speciesmap.js"></script>
<?php }


function getCenter($coordinates) {
  $x=0; $y=0; $z=0; $count=count($coordinates); $errCount=0;
  if ($count == 0) return (object) ['longitude'=>0,'latitude'=>0,'count'=>0,'errCount'=>0];
  foreach ($coordinates as $v) {
    if ($v->latitude == null || $v->longitude == null) {
      $count--; $errCount++;
      continue;
    }
    $lat = $v->latitude * M_PI / 180;
    $long = $v->longitude * M_PI / 180;
    $x += cos($lat) * cos($long);
    $y += cos($lat) * sin($long);
    $z += sin($lat);
  }
  //$x /= $count; $y /= $count; $z /= $count;  //Ndak perlu. |$xyz| ndak akan ubah arah
  
  $base_r = sqrt($x*$x + $y*$y);
  $res = (object) [
    'longitude'=>atan2($y,$x) * 180/M_PI,
    'latitude'=>atan2($z,$base_r) * 180/M_PI,
    'count'=>$count,
    'errCount'=>$errCount
  ];
  return $res;
}

function summarize($findings) {
  $res = [];
  foreach ($findings as $v) {
    if ($v->latitude == null || $v->longitude == null) continue;
    $key = $v->latitude.','.$v->longitude;
    if (!isset($res[$key])) {
      $res[$key] = (object) [
        'latitude'=>$v->latitude,
        'longitude'=>$v->longitude,
        'count'=>1,
        'info'=>$v->localname
      ];
    } else {
      $res[$key]->count++;
      $res[$key]->info .= '<br>'.$v->localname;
    }
  }
  return array_values($res);
}