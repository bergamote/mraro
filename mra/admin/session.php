<?php


session_start();
$msg = false;
$msg_type = false;
if (isset($_GET['eject'])) {
  $_SESSION['login'] = false;
  $msg = 'Logged out';
  $msg_type = 'info';
}
if (isset($_POST['submit']))
{
	$ui_username = trim($_POST['userfield']);
	$ui_password = trim($_POST['passfield']);
  $creds = explode( "\n", file_get_contents('../.creds'), 2);
  $username = trim($creds[0]);
  $password = trim($creds[1]);
	if (($ui_username == $username) && ($ui_password == $password)) {
		$_SESSION['login'] = true;
		$msg = "Succesful login";
		$msg_type = 'success';
	} else {
		$_SESSION['login'] = false;
		$msg = "Error! Try again";
		$msg_type = 'error';
	}
}

if ($_SESSION['login'] != true) {
include('feedback.php');
?>
<html>
<head>
<title> mrAro - Login</title>
<link rel="stylesheet" href="admin_style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="mra_login">


<div id="input">
<img src="logo.png" alt="MR&uarr;RO">
<?= feedback($msg,$msg_type); ?>
<form action="./index.php" method="POST">
Username <input type="text" name="userfield" value="" size="13"><br>
Password <input type="password" name="passfield" value="" size="13"><br><br>
<input type="submit" name="submit" value="Login" class="mra_button">
</form>
</div>

<a href="../../" class="small link">&larr;Home</a>
</body>
<?php
exit;
}
?>
