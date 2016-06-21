<?php
function seedFamilies() {
  $families = file(DIR."/php/builder/rawdata/families.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[];$count=count($families);
  while ($idx < $count) {
    $vals[]='(\''.$families[$idx++]."','$datainfo')";
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO families VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO families VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
