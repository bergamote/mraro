<?php
#mra2 - http://mrarrow.co.uk
include('mra/functions.php');

define("BASE", getcwd());
define("CONTENT", BASE."/content/");
define("CACHE", BASE."/mra/tmp/");
define("UPLOADS", "uploads/");
define("IMAGES", "uploads/img/");
define("THEME_DIR", BASE."/mra/theme/");

$mra = parseConf(BASE."/mra/mra.conf", true);

define("THEME", THEME_DIR.mra('theme','','/'));

$mra['theme_dir'] = "mra/theme/".mra('theme','','/');

function cache_path($home=false) { //create cache path
  $path = $_GET['q'];
  if($home){$path = $home;}
  $mod_time = filemtime(CONTENT.$path);
  $path = str_replace('/','-',$path);
  return CACHE.$path.$mod_time.".html";  
}

if(!empty($_GET['q']))  { // View Page
  $full_path = CONTENT.$_GET['q'];
  if(is_file(cache_path())){
    readfile( cache_path() );
    exit;
  }
  include('mra/lib/markdown.php');
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
  include('mra/lib/markdown.php'); 
  $file = munch(CONTENT.$mra['home_page'], $mra); // Homepage
  $content = $file[0];
  $mra = $file[1]; 
  ob_start(); //start caching
  
  include(THEME.'index.php');
  $buffer = ob_get_flush();
  file_put_contents( $home_cache, $buffer );
  chmod($home_cache, 0664);
  return $buffer;
  exit;
}

function cache_output( $buffer ) {
    file_put_contents( cache_path(), $buffer );
    chmod(cache_path(), 0664);
    return $buffer;
    exit;
}

ob_start( 'cache_output' ); //start caching
include(THEME.'index.php');




