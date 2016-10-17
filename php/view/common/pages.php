<?php
include DIR.'/php/view/tulang.php';

function mainContent() { global $paths;
  if (!isset($paths[1])) { echo '<h2>Page not found</h2>'; return; }
  $page = \SSBIN\Pages::where('WHERE name=:name', ['name'=>$paths[1]]);
  if ($page == null) { echo '<h2>Page not found</h2>'; return; }
  
  echo $page->content;
}