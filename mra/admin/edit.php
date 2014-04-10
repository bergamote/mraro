<?php
define("CONTENT", '../../content/');
include('admin_functions.php');

$path = isset($current_page)?$current_page:urldecode($_GET['page']);

$site_conf = parseConf('../mra.conf',true);
$file = splitConf(CONTENT.$path);
$page_conf = $file[0];
$content = $file[1];

if ($_GET['action'] == 'save') {
  $content = urldecode($_GET['wmd-input']);
  $top = '';
  foreach($page_conf as $k => $v){
    $top .= $k.' = '.$v.PHP_EOL;
  }
  $whole = trim($top).PHP_EOL.PHP_EOL.$content;
  file_put_contents(CONTENT.$path, $whole);
}
$change_time = filemtime(CONTENT.$path);

$mra = array_merge($site_conf,$page_conf);
?>

<form id="page-edit" action="<?= $_SERVER['PHP_SELF'].'?page='.urlencode($path) ?>" method="post">

<input type="text" name="title" id="title_field" value="<?= mra('title')?>"> 
<span class="mra_button" onclick="renameLink('<?=$path?>')" >Rename</span>


<div class="wmd-panel">
  <div id="wmd-button-bar"></div>
  <textarea class="wmd-input" id="wmd-input" name="wmd-input">
<?= $content ?>
  </textarea>
</div>
<span class="mra_button" onclick="ajaxEdit('save','<?= urlencode($path) ?>');">
Save</span>
 <a  class="mra_button" href="../../?q=<?=urlencode($path)?>">View</a> 
 <a  class="mra_button" href="./">Cancel</a> - 
<i>last change: <?= date('D j/m/y \a\t h:i a', $change_time) ?></i>
</form>
<div id="wmd-preview" class="wmd-panel wmd-preview"></div>
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

