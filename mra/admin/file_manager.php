<?php
require_once('session.php');

if($_GET['action'] == 'new') {   //------------------- New item
  $directory = '';
  if(isset($_GET['dir'])) {
    $directory = urldecode($_GET['dir']);
  }
  $new_title = urldecode($_GET['title']);
  if($_GET['type'] == 'page'){
    $new_page_path = $directory.sane($new_title).'.md';
    $auto_content = 'title = '.$new_title;
    $auto_content .= "\n\n# ".$new_title."\n";
    $cmd = 'echo "'.$auto_content.'" > '.CONTENT.$new_page_path;
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
      clearcache();
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
      clearcache();
      return false;            
    } else {
      $msg = "Nothing to delete";
      $msg_type = 'error';
      return false;  
    }
  }
} elseif($_GET['action'] == 'clear-cache') {  // Clear cached pages
  clearcache();
  $msg = "Cache cleared";
  $msg_type = 'success';
  return false;   
}

