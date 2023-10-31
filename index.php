<?php
# mraro index

require_once('mra/functions.php');

# Setup some useful folders as constants.
define("BASE", getcwd());
define("CONTENT", BASE."/content/");
define("CACHE", BASE."/mra/tmp/");
define("UPLOADS", "uploads/");
define("IMAGES", "uploads/img");
define("THEME_DIR", BASE."/mra/theme/");

$mra = parseConf(BASE."/mra/mra.conf", true);

# Set mra[theme] to default theme if 
# no other theme specified in mra.conf.
if( (!empty($mra['theme'])) && (is_dir(THEME_DIR.$mra['theme'])) ) {
  define("THEME", THEME_DIR.$mra['theme'].'/');
} else {
  define("THEME", THEME_DIR);
}
# Set theme directory from mra[theme].
$mra['theme_dir'] = "mra/theme/".mra('theme','','/');

# Check if .sidebar.md is present and 
# store it in mra[sidebar] if yes.
if(is_file(CONTENT.".sidebar.md")) {
  $sidebar = munch(CONTENT.".sidebar.md", $mra);
  $mra['sidebar'] = $sidebar[0];
}

## Check the query string:

# If page is queried
if(!empty($_GET['q'])) { 
  $full_path = CONTENT.urldecode($_GET['q']);
  # If page is already cached, show that,
  if( empty($mra['nocache']) && is_file(cache_path()) ){
    readfile( cache_path() ); 
    exit;
  }
  # otherwise prepare page as $content and $mra
  # (template + cache after conditional check).
  $file = munch($full_path, $mra);
  $content = $file[0];
  $mra = $file[1];
  
# If image is queried
} elseif(!empty($_GET['i'])) { 
  $i = IMAGES.'/'.$_GET['i'];
    if(is_file($i)){
      header("Location: $i");
      die();
    } else {
      $error = IMAGES.'/'."error.png";
      header("Location: $error");
      die();
    }
    
# If nothing is queried, display Home    
# (the first entry in menu.conf).
} else { 
  $menu = parseConf(BASE.'/mra/menu.conf',true);
  $homepage = ($mra['home_page'] == 'auto') ?
    key($menu) : $mra['home_page'];
  # If home is cached, load cache.
  if( empty($mra['nocache']) && is_file(cache_path($homepage)) ){
    readfile( cache_path($homepage) ); 
    exit;
  }
  # otherwise process the homepage and cache it.
  $home_cache = cache_path($homepage);
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
# If no template overide use index.php
$template = "index.php";
if(!empty($mra['template'])) {
  $template = $mra['template'].".php";
}

# Process a normal page
ob_start( 'cache_output' ); //start caching
include(THEME.$template);
