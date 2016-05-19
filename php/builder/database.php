<?php
use Trust\DB;
//DB::setConnection("dumdumts", "fandi", "borera", 34600);
//DB::exec("DROP DATABASE jartoon", []);
//DB::exec("CREATE DATABASE jartoon WITH TEMPLATE = template0 ENCODING = 'UTF8'", []);
//DB::setConnection("jartoon", "fandi", "borera", 34600);
//$tables = ['sysadmin','feedback_produk','diskusi_produk','detil_pesanan','slider_toko',
//    'saldo','produk','pesanan','payment','kategori','user_feedback','toko','inbox','access_log','pengguna'];
//foreach ($tables as $v) DB::exec ("DROP TABLE IF EXISTS $v", []);
//DB::init(true);

$path = DIR."/php/builder/tables";
$dh = opendir($path);
$paths = [];
while ($filename = readdir($dh)) {
  if (in_array($filename, [".","..","index.php"])) continue;
  $paths[] = "$path/$filename";
}
asort($paths);
foreach ($paths as $v) include $v;

try {
  foreach ($queries as $v) {
    echo nl2br(implode("\n", $v)."\n\n\n\n");
    foreach ($v as $sql) DB::exec ($sql, []);
  }
} catch (Exception $ex) {
  echo $ex->getMessage();
}
//\Trust\Debug::print_r(\Trust\Basic::array_merge_inside($queries));

