<?php
function seedIUCNStatus() {
  \Trust\DB::exec("DELETE FROM iucn_status", []);
  
  $status = file(DIR."/php/builder/rawdata/iucn", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());

  foreach ($status as $k=>$v) $status[$k]= explode(",",$v);
  foreach ($status as $k=>$v) $status[$k] = ['abbr'=>$v[0],'long_name'=>$v[1],'data_info'=>$datainfo];
  \SSBIN\IUCN_status::multiInsert($status);
}
