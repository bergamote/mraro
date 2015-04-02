<?php

function cache_path($home=false) { //create cache path
  $path = $_GET['q'];
  if($home){$path = $home;}
  $mod_time = filemtime(CONTENT.$path);
  $path = str_replace('/','-',$path);
  return CACHE.$path.$mod_time.".html";  
}

function cache_output( $buffer ) {
    file_put_contents( cache_path(), $buffer );
    chmod(cache_path(), 0664);
    return $buffer;
    exit;
}

function splitConf($str) {
# Separate configuration header from content
# returns (parsed header [false if none], content)
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

# Parse configutation string OR file into an array
# (comments start with #)
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

# Merge site conf with page conf
# and process content with Markdown
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

# function mra() To use in template.
# Returns a value from the mra array
# with optional string wrapping
function mra($key,$pre = "",$post = "") {
  global $mra;
  return !empty($mra[$key]) ? $pre.$mra[$key].$post : "";
}

function makeMenu($cur = false, $conf = 'mra/menu.conf') {
  $menu_array = parseConf($conf, true);
  
  $mrac = parseConf('mra/mra.conf', true);  
  $homepage = ($mrac['home_page'] == 'auto') ?
   key($menu_array) : $mrac['home_page'];

  foreach ($menu_array as $path => $name) {
    $str .= '<li';
    $str .= ($name == $cur )?  ' class="selected"': '';
    $str .= '><a href="';
    $str .= ($path != $homepage)?'?q='.urlencode($path).'">':'./">';
    $str .= $name;
    $str .= '</a>';
    $str .= '</li>'.PHP_EOL;
  } 
  return $str;
}

function showRaw($file) {
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $file);
  header('Content-type: '.$mime);
  readfile($file);
  exit;
}

function sane($string) { // Name sanitizing function
	$string = strtolower(str_replace(" ", "_", $string));
	$string = preg_replace('![^/a-z0-9_\-.]!', "", $string);
	return $string;
};

?>
