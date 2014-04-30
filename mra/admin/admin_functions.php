<?php
# mra2 - Administrator functions
require_once('session.php');
require_once('../functions.php');


# Encode a configutation array into a string or a file
function encodeConf( $conf, $file = false) {
  foreach($conf as $k => $v) {
    $str .= $k .' = '.$v.PHP_EOL;
  }
  if($file) {
    file_put_contents($file, $str);
  } else {
    return $str.PHP_EOL;
  }
}

function clearcache(){
 $cmd = `rm ../tmp/*`;
}

# Go through a folder recursively and put 
# the structure in an array
function dirToArray($dir) {
  $contents = array();
  foreach (scandir($dir) as $node) {
    if (($node == '.') || ($node == '..')) continue;
    if (is_dir($dir . DIRECTORY_SEPARATOR . $node)) {
      $contents[$node] = dirToArray($dir . DIRECTORY_SEPARATOR . $node);
    } else {
      $contents[] = $node;
    }
  }
  return $contents;
}

# Go through the folder array 
function runArray($theArray, $path = '') {
  echo '<ul>';
  foreach ($theArray as $k => $v) {
    if(is_array($v)) { 
      listCategory($k, $v, $path);
    } else { 
      listPage($v, $path) ;
    }
  }
  addNewPage($path);
  echo '</ul>';
}
function validFiles($name) {
  if( (substr($name, 0, 1) != '.') 
  && (substr($name, -1) != '~')) {
    return true; 
  } else {
    return false;
  }
}

# html list item for Pages
function listPage($name, $path) {
  if(validFiles($name)) {
    echo '<li class="page">';
    $path = (empty($path))?$name:$path.$name;
    $real_path = CONTENT.$path;
    $firstline=`head -n1 $real_path`;
    $conf = parseConf($firstline);
    $title = empty($conf['title'])?'Untitled':$conf['title'];
    menuCheck($path, $title);
    echo $title;
    echo ' <span class="mra_button" onclick="'."ajaxEdit('edit','";
    echo urlencode($path).'\');">Edit</span> ';
    echo ' <span class="mra_small_button" onclick="'."deleteLink('";
    echo urlencode($path).'\');">Delete</span> ';
    echo '</li>'.PHP_EOL;
  }
}
# html unordered list for Category
function listCategory($name, $dir, $path) {
  $path .= $name . DIRECTORY_SEPARATOR;
  $conf = CONTENT. DIRECTORY_SEPARATOR . $path.'.mra';
  $cat_conf = parseConf($conf, true);
  $title = empty($cat_conf['title'])?'Untitled':$cat_conf['title'];
  echo '<li class="category">';
  //menuCheck($path, $title);
  echo $title;
  echo ' <span class="mra_small_button" onclick="'."deleteLink('";
  echo urlencode($path).'\');">Delete</span> ';
  runArray($dir, $path);                
  echo '</li>'.PHP_EOL;
}
# Add New
function addNewPage($path) {
  global $addNewBit;
  echo '<li class="new" id="addNew_'.$addNewBit.'"
    class="mra_buttons">';
  echo '<span class="mra_button" 
    onclick="toggleNew(this, \''.$path.'\')">
        New</span></li>';
  $addNewBit++;
}

# 'In menu' checkbox
function menuCheck($path, $title) {
  echo '<input name="menuSel" type="checkbox" ';
  $menu = parseConf('../menu.conf', true);
  if(array_key_exists($path,$menu)){
    echo 'checked'; 
  }
  echo " onchange=\"ajaxMenu('edit','".$path."','".$title."')\">";
}


?>
