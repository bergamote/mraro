<?php
require_once('admin_functions.php');
define("IMAGES", $_SERVER['DOCUMENT_ROOT'].'/uploads/img/');
define("IMAGES_REL", '../../uploads/img/');
?>

<html>
<head>
<style>
  div.img_thmb {
    width: 150px;
    float: left;
    margin: 0.2em;
    border: 1px solid black;
  }
  div.img_thmb img {
    max-width: 100%;
    max-height: 100%;
  }
</style>
</head>
<body>

<?php
//if they DID upload a file...
if(isset($_POST['submit_image'])) {
  if($_FILES['image']['name'])
  {
	  //if no errors...
	  if(!$_FILES['image']['error'])
	  {
		  //now is the time to modify the future file name and validate the file
		  $new_file_name = strtolower($_FILES['image']['name']); //rename file
		  if($_FILES['image']['size'] > (1024000)) //can't be larger than 1 MB
		  {
			  $valid_file = false;
			  $message = 'Oops!  Your file\'s size is to large.';
		  } else {
		    $valid_file = true;
		  }
      $path = $_FILES['image']['name'];
      $ext = pathinfo($path, PATHINFO_EXTENSION);
      if($ext != 'jpg') {
			  $valid_file = false;
			  $message = 'Oops!  Your file must be a JPEG Image with a .jpg extension.';        
      }
		  //if the file has passed the test
		  if($valid_file)
		  {
			  //move it to where we want it to be
			  move_uploaded_file($_FILES['image']['tmp_name'], IMAGES.$new_file_name);
			  $message = 'Congratulations!  Your has been uploaded.';
		  }
	  }
	  //if there is an error...
	  else
	  {
		  //set that to be the returned message
		  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['image']['error'];
	  }
  }
  echo $message;
} elseif(isset($_GET['delete'])) {
  $del_file = $_GET['delete'];
  if(unlink(IMAGES.$del_file)) {
    echo "File $del_file deleted";
  }
}

?>
<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
	Upload <input type="file" name="image" size="25">
	<input type="submit" name="submit_image" value="Submit">
</form>
<hr>
<?php
$image_list = glob(IMAGES_REL."*.jpg");
usort($image_list, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
foreach (array_reverse($image_list) as $filename) {
    $link = basename($filename);
    echo "<div class=\"img_thmb\"><img src=\"$filename\"><br>/?i=$link <br><a href=\"?delete=$link\">[delete]</a></div>";
}
?>
</body>
</html>



