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

<div id="wrap">

<header class="mra">
<?= mra('site_title') ?>
<nav id="menu_wrap" class="mra" ondragleave="btnVis()">
<ul id="menu_ul" class="sortable" title="Drag-n-drop me!">
<?php
include_once('menu_maker.php');
?>
</ul>

<div id="menu_buttons">
  <button type="button" onclick="ajaxMenu()">Undo</button> 
  <button type="button" onclick="ajaxMenu('save')">Save</button>
</div>

</nav>
</header>

<article>

<div id="main-list">
<?= feedback($msg, $msg_type); ?>
<a href="../../" target="mra_view" class="mra_button">View site</a> 
<a href="./" class="mra_button">Settings</a> 
<a href="session.php?eject=true" id="btn_logout" class="mra_button">Log out</a>

<div id="fileTree" class="hidden">
<ul>
<?php
$addNewBit = 1;
$dirArray = dirToArray(CONTENT);
runArray($dirArray);
?>
</ul>
</div>

<div id="btn_files" onclick="togFileTree()" class="mra_button">Show files</div>

</div>
<a href="http://mraro.com" target="_blank"><img src="logo-small.png" id="mra_logo_small"></a>
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

<aside class="mra">
<?
if(is_file(CONTENT.".sidebar.md")) {
  require_once('../lib/markdown.php');
  $sidebar = munch(CONTENT.".sidebar.md");
  echo $sidebar[0];
}?>
<br>
<a class="mra_button" onclick="editPage('.sidebar.md');">Edit side bar</a>
</aside>

</div> <!-- close #wrap -->

</body>

