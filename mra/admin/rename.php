<?php
require('session.php');
#Rename the file or folder
$old_path = urldecode($_GET['old_path']);
$new_title = urldecode($_GET['new_title']);
$new_path = sane($new_title).".md";



if(!is_file(CONTENT.$old_path)){
  echo CONTENT.$old_path." doesn't exist";
  exit;
}

#Change file Title

$file = splitConf(CONTENT.$old_path);
$header = $file[0];
$header['title'] = $new_title;
$header = encodeConf($header);
file_put_contents(CONTENT.$old_path, $header.$file[1]);


#Move file
$move_cmd = 'mv '.CONTENT.$old_path.' '.CONTENT.$new_path; 
exec($move_cmd);

#Updating menu
$old_menu = parseConf('../menu.conf', true);
if(isset($old_menu[$old_path])){
  $new_menu = array();
  foreach ($old_menu as $path => $title) {
    if ($path == $old_path) {
      $path = $new_path;
      $title = $new_title;
    }
    $new_menu[$path] = $title;
  }
  encodeConf($new_menu, '../menu.conf');
}

#Updating home page in mra.conf
$mra_conf = parseConf('../mra.conf', true);
if($mra_conf['home_page'] == $old_path){
  $mra_conf['home_page'] = $new_path;
  encodeConf($mra_conf, '../mra.conf');
}

return $new_path;

?>
