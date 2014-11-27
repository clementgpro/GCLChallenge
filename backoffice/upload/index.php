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

if(isset($_POST["submit"])) {

	$imgUploaded = $_FILES["fileToUpload"]["tmp_name"];
	$imgDest = "../images/".$_FILES["fileToUpload"]["name"];
	
	// Check if image file is a actual image or fake image
    $check = getimagesize($imgUploaded);
    if($check !== false) {
       move_uploaded_file($imgUploaded, $imgDest);
		
       $destkopImg = pathinfo($imgDest)['dirname']."/".pathinfo($imgDest)['filename']."_desktop.".pathinfo($imgDest)['extension'];
       $mobileImg = pathinfo($imgDest)['dirname']."/".pathinfo($imgDest)['filename']."_mobile.".pathinfo($imgDest)['extension'];
       
		// resize the image to match a width of 900px (desktop)
	   resizeWithProportion($imgDest, $destkopImg, 900);
	   // crop the image to match the height of 360px (desktop)
	   resizeCrop($destkopImg, $destkopImg, 360);
	   
	   // resize the image to match a width of 320px (mobile)
	  resizeWithProportion($imgDest, $mobileImg, 320);
	   // crop the image to match the height of 128px (mobile)
	  resizeCrop($mobileImg, $mobileImg, 128);
	  
	  // remove uploaded file
	  unlink($imgDest);
	   
    } else {
        echo "File is not an image.";
    }
}

function resizeWithProportion($imgSource, $imgDest, $widthSize) {
	
	 $img_size = getimagesize($imgSource);
     $W_Src = $img_size[0]; // largeur
     $H_Src = $img_size[1]; // hauteur
	
	$W = $widthSize;
    $H = $W * ($H_Src / $W_Src);    
	
	$Ress_Dst = imagecreatetruecolor($W,$H);
	$Ress_Src = imagecreatefromjpeg($imgSource);
		 
	imagecopyresampled($Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src); 
	imagejpeg ($Ress_Dst, $imgDest);
	
	// liberation des ressources-image
    imagedestroy ($Ress_Src);
    imagedestroy ($Ress_Dst);
}

function resizeCrop($imgSource, $imgDest, $heightSize) {
	
	 $img_size = getimagesize($imgSource);
     $W_Src = $img_size[0]; // largeur
     $H_Src = $img_size[1]; // hauteur
    
	$H = $heightSize;
    $W = $W_Src;
	
	$Ress_Dst = imagecreatetruecolor($W,$H);
	$Ress_Src = imagecreatefromjpeg($imgSource);
	
	if ($W_Src < $H_Src) {
		$X_Src = 0;
		$X_Dst = 0;
		$W_copy = $W_Src;
	 } else {
		$X_Src = 0;
		$X_Dst = ($W - $W_Src) /2;
		$W_copy = $W_Src;
	 }
	 
	 if ($H_Src > $H) {
		$Y_Src = ($H_Src - $H) /2;
		$Y_Dst = 0;
		$H_copy = $H;
	 } else {
		$Y_Src = 0;
		$Y_Dst = ($H - $H_Src) /2;
		$H_copy = $H_Src;
	 }
		 
	imagecopyresampled($Ress_Dst,$Ress_Src,$X_Dst,$Y_Dst,$X_Src,$Y_Src,$W_copy,$H_copy,$W_copy,$H_copy);
	imagejpeg ($Ress_Dst, $imgDest);
	
	// liberation des ressources-image
    imagedestroy ($Ress_Src);
    imagedestroy ($Ress_Dst);

}

?>