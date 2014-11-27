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
	
	// Check if image file is a actual image or fake image
    $check = getimagesize($imgUploaded);
    if($check !== false) {
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
        echo "File is not an image.";
    }
}
?>