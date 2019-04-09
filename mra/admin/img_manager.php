<?php
require_once('admin_functions.php');
define("IMAGES", $_SERVER['DOCUMENT_ROOT'].'/uploads/img/');
define("IMAGES_REL", '../../uploads/img/'); ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="admin_style.css">
<script src="admin.js"></script>
</head>
<body>

<?php
// if they DID upload a file:
if(isset($_POST['submit_image'])) {
  if($_FILES['image']['name'])
  {
	  // if no errors:
	  if(!$_FILES['image']['error'])
	  {
		  // Modify the future file name and validate the file
		  $new_file_name = strtolower($_FILES['image']['name']);
		  if($_FILES['image']['size'] > (1024000)) { // smaller than 1 MB
			  $valid_file = false;
			  $message = 'Oops!  Your file\'s size is to large.';
		  } else {
		    $valid_file = true;
		  }
      $path = $_FILES['image']['name'];
      $ext = pathinfo($path, PATHINFO_EXTENSION);
      if (!in_array($ext, array("jpg", "png", "gif"))){
			  $valid_file = false;
			  $message = 'Oops!  Your file must be a JPEG Image with a .jpg extension.';        
      }
		  // if file is valid
		  if($valid_file)
		  {
			  // move it to image folder
			  move_uploaded_file($_FILES['image']['tmp_name'], IMAGES.$new_file_name);
			  $message = 'Congratulations!  Your has been uploaded.';
		  }
	  }
	  else
	  {
		  $message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['image']['error'];
	  }
  }
  echo $message;
  
# Deleting an image file.
} elseif(isset($_GET['delete'])) {
  $del_file = urldecode($_GET['delete']);
  if(unlink(IMAGES.$del_file)) {
    echo "File $del_file deleted";
  }
}
?>

<form id="image-upload" action="<?= $_SERVER['PHP_SELF'] ?>"
 method="post" enctype="multipart/form-data">
	Upload <input type="file" name="image" size="25" accept=".jpg, .png, .gif">
	<input type="submit" name="submit_image" value="Submit">
</form>
<br><br>
<ul id="img-gallery">
<?php
$image_list = glob(IMAGES_REL."*.{jpg,png,gif}", GLOB_BRACE);
usort($image_list, create_function('$a,$b', 'return filemtime($a) - filemtime($b);'));
foreach (array_reverse($image_list) as $filename) {
  $link = basename($filename);
  echo "  <li class=\"img_thmb\"><img src=\"$filename\"><br>/?i=$link <br>   
    <a class=\"mra_small_button\" 
     onclick=\"delete_img('$link')\">[delete]</a>
  </li>";
}
?>
</ul>

</body>
</html>



