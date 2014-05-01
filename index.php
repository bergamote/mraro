<?php
# mraro 
require_once('mra/functions.php');

define("BASE", getcwd());
define("CONTENT", BASE."/content/");
define("CACHE", BASE."/mra/tmp/");
define("UPLOADS", "uploads/");
define("IMAGES", "uploads/img/");
define("THEME_DIR", BASE."/mra/theme/");

$mra = parseConf(BASE."/mra/mra.conf", true);

if( (!empty($mra['theme'])) && (is_dir(THEME_DIR.$mra['theme'])) ) {
  define("THEME", THEME_DIR.$mra['theme'].'/');
} else {
  define("THEME", THEME_DIR);
}


$mra['theme_dir'] = "mra/theme/".mra('theme','','/');


if(!empty($_GET['q']))  { // View Page
  $full_path = CONTENT.urldecode($_GET['q']);
  if(is_file(cache_path())){
    readfile( cache_path() );
    exit;
  }
  require_once('mra/lib/markdown.php');
  $file = munch($full_path, $mra);
  $content = $file[0];
  $mra = $file[1];
} elseif(!empty($_GET['i'])) { // Show image
  $i = IMAGES.$_GET['i'];
    if(is_file($i)){
      header("Location: $i");
      die();
    } else {
      $error = IMAGES."error.png";
      header("Location: $error");
      die();
    }
} else {
  if(is_file(cache_path($mra['home_page']))){
    readfile( cache_path($mra['home_page']) );
    exit;
  } 
  $home_cache = cache_path($mra['home_page']); 
  require_once('mra/lib/markdown.php'); 
  $file = munch(CONTENT.$mra['home_page'], $mra); // Homepage
  $content = $file[0];
  $mra = $file[1]; 
  ob_start(); //start caching
  
  require_once(THEME.'index.php');
  $buffer = ob_get_flush();
  file_put_contents( $home_cache, $buffer );
  chmod($home_cache, 0664);
  return $buffer;
  exit;
}

ob_start( 'cache_output' ); //start caching
include(THEME.'index.php');




