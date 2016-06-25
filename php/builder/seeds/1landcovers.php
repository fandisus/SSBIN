<?php
function seedLandcover() {
  \Trust\DB::exec("DELETE FROM landcovers", []);

  $landcovers = file(DIR."/php/builder/rawdata/landcover", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($landcovers as $k=>$v) $landcovers[$k] = ['landcover'=>$v,'data_info'=>$datainfo];
  \SSBIN\Landcover::multiInsert($landcovers);
}
