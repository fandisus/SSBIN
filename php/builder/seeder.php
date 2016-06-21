<?php
$path = DIR."/php/builder/seeds";
$dh = opendir($path);
$paths = [];
while ($filename = readdir($dh)) {
  if (in_array($filename, [".","..","index.php"])) continue;
  $paths[] = "$path/$filename";
}
asort($paths);
foreach ($paths as $v) include $v;

//seedOrganizations();
//seedClasses();
//seedFamilies();
//seedGenus();
//seedSpecies();
//seedIUCNStatus();
//seedIndoStatus();
//seedLocation();
//seedLandcover();
