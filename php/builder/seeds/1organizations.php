<?php
function seedOrganizations() {
  $cats = [
      "Government"=>["BLHD","Forestry"],
      "Corporations"=>["PT REKI","PT Sinar Mas"],
      "Academics"=>["UNSRI","UMP"],
      "NGO"=>['WBH'],
      "Participatory"=>['']
  ];
  foreach ($cats as $cat=>$orgs) {
    foreach ($orgs as $org) {
      $obj = new \SSBIN\Organization(["name"=>$org, "category"=>$cat]);
      $obj->save();
    }
  }
}
