<?php

define("BASE",'../../');
define("CONTENT", BASE.'content/');
define("CACHE", "../tmp/");
define("UPLOADS", BASE."uploads/");
define("IMAGES",UPLOADS.'img/');
define("THEME_DIR", "../theme/");
#include_once('../functions.php');
include_once('admin_functions.php');
include('feedback.php');

if(!empty($_GET['i'])) { // Show image
  $i = IMAGES.$_GET['i'];
    if(is_file($i)){
      header("Location: $i");
      die();
    } else {
      $error = IMAGES."error.png";
      header("Location: $error");
      die();
    }
} elseif($_GET['action'] == 'rename'){
  $current_page = include('rename.php');
}

$mra = parseConf('../mra.conf', true);
?>
<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin</title>
  <link rel="stylesheet" href="../theme/<?= mra('theme') ?>/style.css">
  <link rel="stylesheet" href="admin_style.css">
  <script type="text/javascript" src="../lib/sortable.js"></script>
  <script type="text/javascript" src="../lib/Markdown.Converter.js"></script>
  <script type="text/javascript" src="../lib/Markdown.Editor.js"></script>
  <script type="text/javascript" src="admin.js"></script>
</head>

<body onload="startSort()"<?= mra('class',' class="','"')?>>
<div id="page">
<header id="mra_header">
<?= mra('site_title') ?>
</header>

<div id="menu_maker">
  <div id="menu_wrap">
  <?php
  include('menu_maker.php');
  ?>
  </div>
  <div class="clear-fix"></div>
  <div id="menu_buttons">
    <button type="button" onclick="ajaxMenu()">Undo</button> 
    <button type="button" onclick="ajaxMenu('save')">Save</button>
  </div>
</div>

<div id="main-list">
<?= feedback($msg, $msg_type); ?>
<a href="./" class="mra_button">Settings</a> 
<a href="session.php?eject=true" class="mra_button">Log out</a>
<?php
$dirArray = dirToArray(CONTENT);
runArray($dirArray);
?>
</div>
<article>
<?php
if(isset($current_page)) {

 $cur_url = urlencode($current_page);
 echo "<script>ajaxEdit('edit','".$cur_url."');</script>";
}?>
<div id="main-frame">
<?php

 include('settings.php');

?>
</div>
</article>
</div> <!-- close page -->

</body>

