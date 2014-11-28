<!DOCTYPE html>
<html>
<body>

<form action="index.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>

<?php

include 'ServiceUtils.class.php';

if(isset($_POST["submit"])) {	
	
	$resize = new ServiceUtils();

	$imgUploaded = $_FILES["fileToUpload"]["tmp_name"];
	$imgDest = "../images/".$_FILES["fileToUpload"]["name"];
	echo filesize($imgUploaded);
	// treat only file under 8mo
	if (filesize($imgUploaded) > 8388608) {
		echo "test";
		// copy image without resizing
		move_uploaded_file($imgUploaded, "../images/".pathinfo($image)['filename']."_desktop.".pathinfo($image)['extension']);
		move_uploaded_file($imgUploaded, "../images/".pathinfo($image)['filename']."_mobile.".pathinfo($image)['extension']);
		return;
	}
	
	// Check if image file is a actual image or fake image
    $check = getimagesize($imgUploaded);
    if($check !== false && filesize($imgUploaded) <= 8388608) {
       move_uploaded_file($imgUploaded, $imgDest);
	   
		$ext = pathinfo($imgDest)['extension'];
		$destkopImg = pathinfo($imgDest)['dirname']."/".pathinfo($imgDest)['filename']."_desktop.".$ext;
		$mobileImg = pathinfo($imgDest)['dirname']."/".pathinfo($imgDest)['filename']."_mobile.".$ext;
       
		// resize the image to match a width of 900px (desktop)
	   $resize->resizeWithProportion($imgDest, $destkopImg, 900, $ext);
	   // crop the image to match the height of 360px (desktop)
	   $resize->resizeCrop($destkopImg, $destkopImg, 360, $ext);
	   
	   // resize the image to match a width of 320px (mobile)
	  $resize->resizeWithProportion($imgDest, $mobileImg, 320, $ext);
	   // crop the image to match the height of 128px (mobile)
	  $resize->resizeCrop($mobileImg, $mobileImg, 128, $ext);
	  
	  // remove uploaded file
	  unlink($imgDest);
	   
    } else {
        echo "<strong>File is not an image</strong>";
    }
}
?>