<?php
include DIR."/php/view/tulang.php";

function htmlHead() {

}

function mainContent() { global $test;
  $fake = \Faker\Factory::create();
  for ($i=1; $i<=20; $i++) {
    echo "$fake->name <br />";
  }
  
  echo "<pre>".print_r($_SERVER,true)."</pre>";
}
