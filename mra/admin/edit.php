<?php
define("CONTENT", '../../content/');
require_once('admin_functions.php');

require_once('feedback.php');
$msg = false;
$msg_type = false;

$path = isset($current_page) ?
  $current_page : urldecode($_GET['page']);

$site_conf = parseConf('../mra.conf',true);
$file = splitConf(CONTENT.$path);
$page_conf = $file[0];
$content = $file[1];

if ($_GET['action'] == 'save') { 
  $new_title = urldecode($_GET['new_title']);
  if($new_title != $page_conf['title']){ // move file if title changed
    $page_conf['title'] = $new_title;
    $old_file = strlen(basename($path));
    $new_path = substr($path,0, -$old_file);
    $new_path .= sane($new_title).".md";
    $move_cmd = 'mv '.CONTENT.$path.' '.CONTENT.$new_path; 
    exec($move_cmd);
    $old_menu = parseConf('../menu.conf', true); // Update menu
    if(isset($old_menu[$path])){
      $new_menu = array();
      foreach ($old_menu as $p => $t) {
        if ($p == $path) {
          $p = $new_path; $t = $new_title;
        }
        $new_menu[$p] = $t;
      }
      encodeConf($new_menu, '../menu.conf');
    }
    if($site_conf['home_page'] == $path){ // Update homepage
      $site_conf['home_page'] = $new_path;
      encodeConf($site_conf, '../mra.conf');
    }
    $path = $new_path;
  }
  $content = urldecode($_GET['wmd-input']);
  $top = '';
  foreach($page_conf as $k => $v){
    $top .= $k.' = '.$v.PHP_EOL;
  }
  $whole = trim($top).PHP_EOL.PHP_EOL.$content;
  if(file_put_contents(CONTENT.$path, $whole)) {
    $msg = "File saved";
    $msg_type = 'success';
  } else {
    $msg = "Error! File not saved";
    $msg_type = 'error';
  }
  return $path;
}
$change_time = filemtime(CONTENT.$path);

$mra = array_merge($site_conf,$page_conf);

?>
<form id="page-edit" action="<?= $_SERVER['PHP_SELF'].'?page='.urlencode($path) ?>" method="post">
<?
echo feedback($msg,$msg_type);

if($path == ".sidebar.md"){ ?>
Editing side bar
<input type="hidden" name="title" id="title_field" value=".sidebar"> 
<?php }else{ ?>
Title: <input onkeydown="return (event.keyCode!=13)" type="text" 
name="title" id="title_field" value="<?= mra('title')?>"> 
<?php } ?>
 <a class="mra_button" target="_blank" href="img_manager.php">Images</a>

<div class="wmd-panel">
  <div id="wmd-button-bar"></div>
<textarea class="wmd-input" id="wmd-input" name="wmd-input"
 onfocus="addEv('<?= urlencode($path) ?>')" onblur="removeEv('<?= urlencode($path) ?>')">
<?= $content ?>
</textarea>
</div>
<span class="mra_button" onclick="saveEdit('<?= urlencode($path) ?>');">Save</span>
 <a  class="mra_button" href="../../?q=<?= urlencode($path)?>" target="mra_view">View</a> 
 <a  class="mra_button" href="./">Cancel</a> - 
<i class="small">last saved: <?= date('D j/m/y \a\t h:i a', $change_time) ?></i>
</form>
<div id="wmd-preview" class="wmd-panel wmd-preview<?= mra("class",' ') ?>"></div>
<?php
if(isset($current_page)) {
?>
<script>
(function () {
  var converter = new Markdown.Converter();
  var help = function () { alert("Do you need help?"); }
  var options = {
    helpButton: { handler: help },
    strings: { quoteexample: "put you're quote right here" }
    };
  var editor = new Markdown.Editor(converter, "", options);
  editor.run();
})();
</script>
<?php
};

