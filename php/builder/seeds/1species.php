<?php
function seedSpecies() {
  $species = file(DIR."/php/builder/rawdata/species.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[];$count=count($species);
  while ($idx < $count) {
    $vals[]='(\''.$species[$idx++]."','$datainfo')";
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO species VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO species VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
