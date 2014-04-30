<?php
require_once('session.php');

if($_GET['action'] == 'rename') {      //--------------- Rename item
  $old_path = urldecode($_GET['old_path']);
  $old_file = strlen(basename($old_path));
  $new_path = substr($old_path,0, -$old_file);
  $new_title = urldecode($_GET['new_title']);
  $new_file = sane($new_title).".md";
  $new_full_path = $new_path.$new_file;
  if(!is_file(CONTENT.$old_path)){
    $msg =  CONTENT.$old_path." doesn't exist";
    $msg_type = 'error';
    return false;
  }
  #Change file Title
  $file = splitConf(CONTENT.$old_path);
  $header = $file[0];
  $header['title'] = $new_title;
  $header = encodeConf($header);
  file_put_contents(CONTENT.$old_path, $header.$file[1]);
  #Move file
  if($old_path == $new_full_path) {
    $msg =  'Page successfully renamed';
    $msg_type = 'success';
    return $old_path;
  }
  $move_cmd = 'mv '.CONTENT.$old_path.' '.CONTENT.$new_full_path; 
  exec($move_cmd);
  #Updating menu
  $old_menu = parseConf('../menu.conf', true);
  if(isset($old_menu[$old_path])){
    $new_menu = array();
    foreach ($old_menu as $path => $title) {
      if ($path == $old_path) {
        $path = $new_full_path;
        $title = $new_title;
      }
      $new_menu[$path] = $title;
    }
    encodeConf($new_menu, '../menu.conf');
  }
  #Updating home page in mra.conf
  $mra_conf = parseConf('../mra.conf', true);
  if($mra_conf['home_page'] == $old_path){
    $mra_conf['home_page'] = $new_full_path;
    encodeConf($mra_conf, '../mra.conf');
  }
  $msg =  'Page successfully renamed';
  $msg_type = 'success';
  return $new_full_path;
  
} elseif($_GET['action'] == 'new') {   //------------------- New item
  $directory = '';
  if(isset($_GET['dir'])) {
    $directory = urldecode($_GET['dir']);
  }
  $new_title = urldecode($_GET['title']);
  if($_GET['type'] == 'page'){
    $new_page_path = $directory.sane($new_title).'.md';
    $cmd = 'echo "title = '.$new_title.'\n" > '.CONTENT.$new_page_path;
    exec($cmd);
    $msg = "Page '$new_title' created";
    $msg_type = 'success';
    return $new_page_path;
  } elseif($_GET['type'] == 'category') {
    $new_page_path = $directory.sane($new_title);
    $cmd = 'mkdir '.CONTENT.$new_page_path;
    exec($cmd);
    $cmd = 'echo "title = '.$new_title.'\n" > '.CONTENT.$new_page_path.'/.mra';
    exec($cmd);  
    $msg = "Folder '$new_title' created";
    $msg_type = 'success';
    return false;
  }
} elseif($_GET['action'] == 'delete') {   //----------------- Delete item
  if(isset($_GET['file'])) {
    $del_file = urldecode($_GET['file']);
    $menu = parseConf('../menu.conf', true);
    if($menu[$del_file]){
      unset($menu[$del_file]);
      encodeConf($menu, '../menu.conf');
    }
    if((is_file(CONTENT.$del_file)) && (!is_dir(CONTENT.$del_file))){   
      $cmd = 'rm '.CONTENT.$del_file;
      exec($cmd);
      $msg = "File deleted";
      $msg_type = 'success';
      return false; 
    } elseif(is_dir(CONTENT.$del_file)) {
      $menu = parseConf('../menu.conf', true);
      foreach($menu as $k => $v){
        if(substr($k, 0 , strlen($del_file)) == $del_file){
          unset($menu[$k]);
        }
      }
      encodeConf($menu, '../menu.conf');
      $cmd = 'rm -r '.CONTENT.$del_file;
      exec($cmd);
      $msg = "Category deleted";   
      $msg_type = 'success';
      return false;            
    } else {
      $msg = "Nothing to delete";
      $msg_type = 'error';
      return false;  
    }
  }
} elseif($_GET['action'] == 'clear-cache') {
  clearcache();
  $msg = "Cache cleared";
  $msg_type = 'success';
  return false;   
}



