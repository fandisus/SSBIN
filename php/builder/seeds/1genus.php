<?php
function seedGenus() {
  \Trust\DB::exec("DELETE FROM genus", []);
  
  $genus = file(DIR."/php/builder/rawdata/genus.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($genus as $k=>$v) $genus[$k] = (object)['genus'=>$v,'data_info'=>$datainfo];
  \SSBIN\Genus::multiInsert($genus);
}
