<?php
# mraro 
require_once('mra/functions.php');

define("BASE", getcwd());
define("CONTENT", BASE."/content/");
define("CACHE", BASE."/mra/tmp/");
define("UPLOADS", "uploads/");
define("IMAGES", "uploads/img");
define("THEME_DIR", BASE."/mra/theme/");

$mra = parseConf(BASE."/mra/mra.conf", true);

if( (!empty($mra['theme'])) && (is_dir(THEME_DIR.$mra['theme'])) ) {
  define("THEME", THEME_DIR.$mra['theme'].'/');
} else {
  define("THEME", THEME_DIR);
}

$mra['theme_dir'] = "mra/theme/".mra('theme','','/');

if(is_file(CONTENT.".sidebar.md")) {
  require_once('mra/lib/markdown.php');
  $sidebar = munch(CONTENT.".sidebar.md");
  $mra['sidebar'] = $sidebar[0];
}

if(!empty($_GET['q']))  { // Display Page
  $full_path = CONTENT.urldecode($_GET['q']);
  if( empty($mra['nocache']) && is_file(cache_path()) ){
    readfile( cache_path() ); // Load Cached Page
    exit;
  }
  require_once('mra/lib/markdown.php');
  $file = munch($full_path, $mra);
  $content = $file[0];
  $mra = $file[1];
} elseif(!empty($_GET['i'])) { // Show image
  $i = IMAGES.'/'.$_GET['i'];
    if(is_file($i)){
      header("Location: $i");
      die();
    } else {
      $error = IMAGES.'/'."error.png";
      header("Location: $error");
      die();
    }
} else { // Display Home
  $menu = parseConf(BASE.'/mra/menu.conf',true);
  $homepage = ($mra['home_page'] == 'auto') ?
    key($menu) : $mra['home_page'];

  if( empty($mra['nocache']) && is_file(cache_path($homepage)) ){
    readfile( cache_path($homepage) ); // Load Cached Home
    exit;
  }
  $home_cache = cache_path($homepage); 
  require_once('mra/lib/markdown.php');
  $file = munch(CONTENT.$homepage, $mra); 
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




