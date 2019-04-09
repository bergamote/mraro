<?php
# mraro functions

## Caching functions:
# Create cache path based on last mod time.
function cache_path($home=false) { 
  if (isset($_GET['q'])) {
    $path = $_GET['q'];
  }
  if($home){$path = $home;}
  $mod_time = filemtime(CONTENT.$path);
  $path = str_replace('/','-',$path);
  return CACHE.$path.$mod_time.".html";
}
# Save stream output to file.
function cache_output( $buffer ) {
    file_put_contents( cache_path(), $buffer );
    chmod(cache_path(), 0664);
    return $buffer;
    exit;
}

# Separate configuration header from content
# returns (parsed header [false if none], content)
function splitConf($str) {
  $text = file_get_contents($str);
  $part = explode("\n\n",$text,2);
  $conf_line = explode("\n",$part[0]);
  $valid = true;
  foreach ($conf_line as $line) {
    if(substr_count($line, "=") != 1) {
    # Require one = sign on each line
      $valid = false;
    }
  }
  $conf_array = ($valid) ? parseConf($part[0]) : false;
  return array($conf_array,$part[1]);
}

# Returns parsed configutation string OR file 
# as an array. Lines starting with # are comments.
function parseConf($conf, $file = false) {
  if($file) { $conf = file_get_contents($conf); }
  $conf_array = array();
  $lines = explode("\n",$conf);
  foreach ($lines as $line) {
    $line = trim($line);
    if(empty($line) ||
      (substr($line, 0, 1) == '#') ||
      (substr_count($line, "=") != 1)
      ) {
      continue;
    }
    $pair = explode('=', $line);
    $key = trim($pair[0]);
    $conf_array[$key] = trim($pair[1]);
  }
  return $conf_array;
}

# Merge site conf with page conf and returns
# marked-down content and mra array
function munch($path, $mra) {
  if(!is_file($path)) {
    return array(
      "<h1>404</h1>This page doesn't exist",
      $mra);
  }
  $file = splitConf($path);
  if($file[0]) {
    $mra = array_merge($mra, $file[0]);
  }
  $content = Markdown($file[1]);
  return array($content, $mra);
}

# mra( key , string , string ) 
# Returns a value from the mra array
# with optional string wrapping
function mra($key,$pre = "",$post = "") {
  global $mra;
  return !empty($mra[$key]) ? $pre.$mra[$key].$post : "";
}

# makeMenu( mra('title') , menu file )
# Returns a HTML unordered list of links to the
# pages in the menu file. False if only one entry,
function makeMenu($cur = false, $conf = 'mra/menu.conf') {
  $menu_array = parseConf($conf, true);

  $mrac = parseConf('mra/mra.conf', true);
  $homepage = ($mrac['home_page'] == 'auto') ?
   key($menu_array) : $mrac['home_page'];
  $str = '';
  foreach ($menu_array as $path => $name) {
    $q = '/?q=';
    $link_path = $path;
    if($mrac['nginx_url']) {
      $q = '/';
      $link_path = substr($path, 0, -3);
    }
    $str .= '<li'; 
    # If current page title is in page list,
    # mark it as currently selected.
    $str .= ($name == $cur )?  ' class="selected"': '';
    $str .= '><a href="';
    $str .= ($path != $homepage)?
      $q.$link_path.'">' : '/">';
    $str .= $name;
    $str .= '</a>';
    $str .= '</li>'.PHP_EOL;
  }
  if (count($menu_array) > 1) {
    return $str;
  }
}

function showRaw($file) {
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file);
  header('Content-type: '.$mime);
  readfile($file);
  exit;
}

# String sanitizing function for 
# file names, etc...
function sane($string) { 
	$string = strtolower(str_replace(" ", "_", $string));
	$string = preg_replace('![^/a-z0-9_\-.]!', "", $string);
	return $string;
};

?>
