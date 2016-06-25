<?php
function seedSpecies() {
  \Trust\DB::exec("DELETE FROM species", []);
  
  $species = file(DIR."/php/builder/rawdata/species.txt", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  
  foreach ($species as $k=>$v) $species[$k] = ['species'=>$v,'data_info'=>$datainfo];
  \SSBIN\Species::multiInsert($species);
}
