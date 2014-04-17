<?php
# Edit yourself
$username = 'user';
$password = 'pass';

session_start();
$msg = '';
if (isset($_GET['eject'])) {
  $_SESSION['login'] = false;
  echo 'You are logged out<br><a href="../../">Return to home page</a>';
  exit;
}
if (isset($_POST['submit']))
{
	$ui_username = trim($_POST['userfield']);
	$ui_password = trim($_POST['passfield']);
	if (($ui_username == $username) && ($ui_password == $password)) {
		$_SESSION['login'] = true;
		$msg = "<br>Succesfully logged in.<br>";
	} else {
		$_SESSION['login'] = false;
		$msg = "<br>Error! Try again.<br>";
	}
}
if ($_SESSION['login'] != true) {
?>
<br>
<?php if($msg!='')echo $msg; ?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
username <input type="text" name="userfield" value="" size="13"><br>
password <input type="password" name="passfield" value="" size="13"><br>
<input type="submit" name="submit" value="Log in"><br>

</form>

<?php
exit;
}
?>
