<?php
require_once('admin_functions.php');

$old_menu = parseConf('../menu.conf', true);


if(isset($_GET['menu'])) {
  $order_menu = $_GET['menu'];
  $new_menu = array();
  foreach($order_menu as $v) {
    $path = array_keys($old_menu, $v);
    $k = $path[0];
    $new_menu[$k] = urldecode($v); 
  }
  encodeConf($new_menu, '../menu.conf');
  clearcache();
}
elseif(isset($_GET['edit'])) {
  $edit = $_GET['edit'];
  $path = urldecode($edit[0]);
  $title = urldecode($edit[1]);
  if(array_key_exists($path,$old_menu)){
    $new_menu = $old_menu;
    unset($new_menu[$path]);
  } else {
    $new_menu = $old_menu;
    $new_menu[$path] = $title;
  }
  encodeConf($new_menu, '../menu.conf');
  clearcache();
}

$menu_array = parseConf('../menu.conf', true);
foreach ($menu_array as $name) {
  echo '<li>'.$name.'</li>'.PHP_EOL;
}
?>

