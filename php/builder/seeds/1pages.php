<?php
function seedPages() {
  \Trust\DB::exec("DELETE FROM pages", []);
  \Trust\DB::exec("ALTER SEQUENCE pages_id_seq RESTART", []);

  $datainfo = json_encode(\Trust\Model::newDataInfo());
  $pages = [
    ['name'=>'About SSBIN', 'position'=>'menubar','group_name'=>'','order_no'=>1,'content'=>'','data_info'=>$datainfo],
    ['name'=>'Privacy statements and disclaimer', 'position'=>'footer','group_name'=>'Terms of use','order_no'=>1,'content'=>'','data_info'=>$datainfo],
    ['name'=>'Data sharing agreement', 'position'=>'footer','group_name'=>'Terms of use','order_no'=>2,'content'=>'','data_info'=>$datainfo],
    ['name'=>'Data use agreement', 'position'=>'footer','group_name'=>'Terms of use','order_no'=>3,'content'=>'','data_info'=>$datainfo],    
  ];
  \SSBIN\Pages::multiInsert($pages);
}
