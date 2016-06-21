<?php
function seedClasses() {
  $classes = file(DIR.'/php/builder/rawdata/classes.txt', FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  $idx=0; $sqls=[]; $vals=[]; $count=count($classes);
  while ($idx < $count) {
    $vals[]='(\''.$classes[$idx++]."','$datainfo')";
    if ($idx % 10000 == 0) {
      $sqls[] = 'INSERT INTO classes VALUES '.implode(',', $vals);
      $vals=[];
    }
  }
  $sqls[] = 'INSERT INTO classes VALUES '.implode(',', $vals);
  foreach ($sqls as $s) \Trust\DB::exec($s,[]);
}
