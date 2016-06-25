<?php
function seedClasses() {
  \Trust\DB::exec("DELETE FROM classes", []);
  
  $classes = file(DIR.'/php/builder/rawdata/classes.txt', FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($classes as $k=>$v) $classes[$k] = ['class'=>$v,'data_info'=>$datainfo];
  \SSBIN\Classes::multiInsert($classes);
}
