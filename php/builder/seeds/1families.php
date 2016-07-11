<?php
function seedFamilies() {
  \Trust\DB::exec("DELETE FROM families", []);

  $families = file(DIR."/php/builder/rawdata/families.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($families as $k=>$v) $families[$k] = (object)['family'=>$v,'data_info'=>$datainfo];
  \SSBIN\Families::multiInsert($families);
}
