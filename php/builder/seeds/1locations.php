<?php
function seedLocation() {
  $locations = file(DIR."/php/builder/rawdata/location", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[];$count=count($locations);
  while ($idx < $count) {
    $vals[]='(\''.$locations[$idx++]."','$datainfo')";
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO locations VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO locations VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
