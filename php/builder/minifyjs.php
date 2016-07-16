<?php
use Trust\Files;
use MatthiasMullie\Minify;

$publicPath = DIR.'/public/js';
$internalPath = DIR.'/js';
$files = Files::GetDirFiles(DIR.'/public/js');
\Trust\Basic::flatten_array($files);

if (!isset($_GET['a'])) {
  echo nl2br("?a=copy to copy public js to internal js\n?a=minify to minify public js\n?a=unminify to revert to original");
  die();
}
$a = $_GET['a'];

//COPY public js TO internal js
if ($a == 'copy') {
  Files::recurse_copy($publicPath, $internalPath);
}
//MINIFY: minify internal js, and put INTO public js. DANGER!
if ($a == 'minify') {
  //throw new Exception('YOU NEED TO MANUALLY ENABLE THIS DANGEROUS METHOD FIRST');
  $publiclen = strlen($publicPath);
  foreach ($files as $v) {
    $srcpath = $internalPath.substr($v, $publiclen);
    $dstpath = $v;
    
    //Using google Closure Compiler
    $opost = [
      'js_code'=>file_get_contents($srcpath),
      'compilation_level'=>'SIMPLE_OPTIMIZATIONS',
      'output_format'=>'text',
      'output_info'=>'compiled_code'
    ];
    $postdata='';
    foreach ($opost as $k=>$v) $postdata .= $k.'='.urlencode($v).'&';
    $postdata = trim($postdata,'&');
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL=>'http://closure-compiler.appspot.com/compile',
      CURLOPT_POST=>true,
      CURLOPT_POSTFIELDS=>$postdata,
      CURLOPT_RETURNTRANSFER=>true
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $fh = fopen($dstpath,'w') or die('Unable to open file!');
    fwrite($fh,$response);
    fclose($fh);
    
//    
//    Minify
//    $mini = new Minify\JS($srcpath);
//    $mini->minify($dstpath);
  }
}
if ($a == 'unminify') {
  Files::recurse_copy($internalPath, $publicPath);
}
