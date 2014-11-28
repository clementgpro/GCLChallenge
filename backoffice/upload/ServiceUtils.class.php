<?php
include 'BMP.php';
class ServiceUtils {
	
	function resizeWithProportion($imgSource, $imgDest, $widthSize, $ext) {
		ini_set("gd.jpeg_ignore_warning", 1);
		
		$img_size = getimagesize ( $imgSource );
		$W_Src = $img_size [0]; // largeur
		$H_Src = $img_size [1]; // hauteur
		
		$W = $widthSize;
		$H = $W * ($H_Src / $W_Src);
		
		$Ress_Dst = imagecreatetruecolor ( $W, $H );
		
		if ($ext == 'jpg' || $ext == 'jpeg') {
			$Ress_Src = imagecreatefromjpeg ( $imgSource );
		} else if ($ext == 'png') {
			$Ress_Src = imagecreatefrompng ( $imgSource );
		} else if ($ext == 'bmp') {
			$Ress_Src = Bmp::imagecreatefrombmp ( $imgSource );
		} else if ($ext == 'gif') {
			$Ress_Src = imagecreatefromgif ( $imgSource );
		} else {
			echo 'extension unknown';
		}
		
		if (!$Ress_Src) {
			 /* Création d'une image vide */
			$Ress_Src  = imagecreatetruecolor(150, 30);
			$bgc = imagecolorallocate($Ress_Src, 255, 255, 255);
			$tc  = imagecolorallocate($Ress_Src, 0, 0, 0);

			imagefilledrectangle($Ress_Src, 0, 0, 150, 30, $bgc);

			/* On y affiche un message d'erreur */
			imagestring($Ress_Src, 1, 5, 5, 'Erreur de chargement : image corrompu ', $tc);
		}
		
		imagecopyresampled ( $Ress_Dst, $Ress_Src, 0, 0, 0, 0, $W, $H, $W_Src, $H_Src );
		
		if ($ext == 'jpg' || $ext == 'jpeg') {
			imagejpeg ( $Ress_Dst, $imgDest, 90 );
		} else if ($ext == 'png') {
			imagepng ( $Ress_Dst, $imgDest);
		} else if ($ext == 'bmp') {
			imagebmp ( $Ress_Dst, $imgDest, 90 );
		} else if ($ext == 'gif') {
			imagegif ( $Ress_Dst, $imgDest, 90 );
		} else {
			echo 'extension unknown';
		}
		
		// liberation des ressources-image
		imagedestroy ( $Ress_Src );
		imagedestroy ( $Ress_Dst );
	}
	
	function resizeCrop($imgSource, $imgDest, $heightSize, $ext) {
		ini_set("gd.jpeg_ignore_warning", 1);
	
		$img_size = getimagesize ( $imgSource );
		$W_Src = $img_size [0]; // largeur
		$H_Src = $img_size [1]; // hauteur
		
		$H = $heightSize;
		$W = $W_Src;
		
		$Ress_Dst = imagecreatetruecolor ( $W, $H );
		
		if ($ext == 'jpg' || $ext == 'jpeg') {
			$Ress_Src = imagecreatefromjpeg ( $imgSource );
		} else if ($ext == 'png') {
			$Ress_Src = imagecreatefrompng ( $imgSource );
		} else if ($ext == 'bmp') {
			$Ress_Src = Bmp::imagecreatefrombmp ( $imgSource );
		} else if ($ext == 'gif') {
			$Ress_Src = imagecreatefromgif ( $imgSource );
		} else {
			echo 'extension unknown';
		}
		
		if (!$Ress_Src) {
			 /* Création d'une image vide */
			$Ress_Src  = imagecreatetruecolor(150, 30);
			$bgc = imagecolorallocate($Ress_Src, 255, 255, 255);
			$tc  = imagecolorallocate($Ress_Src, 0, 0, 0);

			imagefilledrectangle($Ress_Src, 0, 0, 150, 30, $bgc);

			/* On y affiche un message d'erreur */
			imagestring($Ress_Src, 1, 5, 5, 'Erreur de chargement : image corrompu ' . $imgname, $tc);
		}
		
		if ($W_Src < $H_Src) {
			$X_Src = 0;
			$X_Dst = 0;
			$W_copy = $W_Src;
		} else {
			$X_Src = 0;
			$X_Dst = ($W - $W_Src) / 2;
			$W_copy = $W_Src;
		}
		
		if ($H_Src > $H) {
			$Y_Src = ($H_Src - $H) / 2;
			$Y_Dst = 0;
			$H_copy = $H;
		} else {
			$Y_Src = 0;
			$Y_Dst = ($H - $H_Src) / 2;
			$H_copy = $H_Src;
		}
		
		imagecopyresampled ( $Ress_Dst, $Ress_Src, $X_Dst, $Y_Dst, $X_Src, $Y_Src, $W_copy, $H_copy, $W_copy, $H_copy );
		
		if ($ext == 'jpg' || $ext == 'jpeg') {
			imagejpeg ( $Ress_Dst, $imgDest, 90 );
		} else if ($ext == 'png') {
			imagepng ( $Ress_Dst, $imgDest);
		} else if ($ext == 'bmp') {
			imagebmp ( $Ress_Dst, $imgDest, 90 );
		} else if ($ext == 'gif') {
			imagegif ( $Ress_Dst, $imgDest, 90 );
		} else {
			echo 'extension unknown';
		}
		
		// liberation des ressources-image
		imagedestroy ( $Ress_Src );
		imagedestroy ( $Ress_Dst );
	}
	
	function list_images($pathToDir, $onlyBasename=false) {
	
		// authorized extensions
		$ext = array('jpg', 'jpeg', 'bmp', 'png');
		$ret = array();
		if ($handle = opendir ( $pathToDir )) {			
			while ( false !== ($entry = readdir ( $handle )) ) {
				if (in_array(pathinfo($entry)['extension'], $ext)) {
					$ret[] = ($onlyBasename) ? $entry : $pathToDir.$entry;
				}
			}
			closedir ( $handle );
		}
		return $ret;
	}
	
	function get_description_from_prop($pathToProp) {
		$descr = "";
		if (file_exists($pathToProp)) {
			$content = file_get_contents($pathToProp);
			$index = strpos($content, "Description");
			if ($index > 0) {
				$index += 12;
				$descr = substr($content, $index, strlen($content));
			}			
		}
		return $descr;
	}
}

?>