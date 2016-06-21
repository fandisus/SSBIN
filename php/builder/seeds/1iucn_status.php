<?php
function seedIUCNStatus() {
  $status = file(DIR."/php/builder/rawdata/iucn", FILE_IGNORE_NEW_LINES);
  foreach ($status as $k=>$v) $status[$k]=  explode(",",$v);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[];$count=count($status);
  while ($idx < $count) {
    $vals[]='(\''.$status[$idx][0].'\',\''.$status[$idx][1]."','$datainfo')";
    $idx++;
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO iucn_status VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO iucn_status VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
