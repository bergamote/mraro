<?php

define("BASE",'../../');
define("CONTENT", BASE.'content/');
define("CACHE", "../tmp/");
define("UPLOADS", BASE."uploads/");
define("IMAGES",UPLOADS.'img/');
define("THEME_DIR", "../theme/");
#include_once('../functions.php');
require_once('admin_functions.php');
require_once('feedback.php');

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
} elseif(isset($_GET['action'])){

  if($_GET['action'] == 'save') {
    $current_page = include('edit.php');
  } elseif (isset($_GET['page'])) {
    $current_page = $_GET['page'];
  } else {
    $current_page = require_once('file_manager.php');
  }
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

<body onload="startSort();checkFileTree()"<?= mra('class',' class="','"')?>>
<div id="page">
<header id="mra_header">
<?= mra('site_title') ?>
</header>

<div id="menu_maker">
  <div id="menu_wrap">
  <?php
  include_once('menu_maker.php');
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

<a href="../../" target="mra_view" class="mra_button">View site</a> 
<a href="./" class="mra_button">Settings</a> 
<a href="session.php?eject=true" id="btn_logout" class="mra_button">Log out</a>

<div id="fileTree" class="hidden">
<?php
$addNewBit = 1;
$dirArray = dirToArray(CONTENT);
runArray($dirArray);
?>
</div>
<div id="btn_files" onclick="togFileTree()" class="mra_button">Show files</div>

</div>
<article>
<div id="main-frame">
<?php
if($current_page) {
  $_GET['action'] = 'edit';
  include('edit.php');
} else {
  include('settings.php');
}
?>
</div>
</article>
</div> <!-- close page -->

</body>

