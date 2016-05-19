<?php
spl_autoload_register(function($className) {
  $className = ltrim($className, '\\');
  $firstSlash = strpos($className, '\\');
  $firstNs = substr($className,0,$firstSlash);
  $remaining = substr($className, $firstSlash+1);
  if ($firstNs == "SSBIN") {
    $filename = __DIR__.DS."$remaining.php";
    if (file_exists($filename)) { require $filename; return true; }
  }
  return false;
});
