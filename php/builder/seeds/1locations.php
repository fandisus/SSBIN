<?php
function seedLocation() {
  \Trust\DB::exec("DELETE FROM locations", []);

  $locations = file(DIR."/php/builder/rawdata/location", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($locations as $k=>$v) $locations[$k] = (object)['location'=>$v,'data_info'=>$datainfo];
  \SSBIN\Location::multiInsert($locations);
}
