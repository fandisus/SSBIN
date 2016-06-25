<?php
function seedGrids() {
  \Trust\DB::exec("DELETE FROM grids", []);
  
  $grids = file(DIR."/php/builder/rawdata/grid", FILE_IGNORE_NEW_LINES);
  $datainfo = json_encode(\Trust\Model::newDataInfo());
  foreach ($grids as $k=>$v) $grids[$k] = ['grid'=>$v,'data_info'=>$datainfo];
  \SSBIN\Grid::multiInsert($grids);
}
