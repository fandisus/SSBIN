<?php
function seedGenus() {
  $genus = file(DIR."/php/builder/rawdata/genus.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[];$count=count($genus);
  while ($idx < $count) {
    $vals[]='(\''.$genus[$idx++]."','$datainfo')";
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO genus VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO genus VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
