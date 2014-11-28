<?php 

	require_once 'upload/ServiceUtils.class.php';

	$service = new ServiceUtils();
	$fileFromServ = $service->list_images(__DIR__."/../../gclcimages/");
	$fileClient = $service->list_images(__DIR__."/images/desktop/", true);
	
	foreach($fileFromServ as $image) {
		
		if (!in_array(pathinfo($image)['basename'], $fileClient)) {
			
			if (filesize($image) <= 8388608) {				
				$ext = pathinfo($image)['extension'];
				$destkopImg =__DIR__."/images/desktop/".pathinfo($image)['basename'];
				$mobileImg = __DIR__."/images/mobile/".pathinfo($image)['basename'];
			
				// service the image to match a width of 900px (desktop)
				$service->resizeWithProportion($image, $destkopImg, 900, $ext);
				// crop the image to match the height of 360px (desktop)
				$service->resizeCrop($destkopImg, $destkopImg, 360, $ext);
			
				// resize the image to match a width of 320px (mobile)
				$service->resizeWithProportion($image, $mobileImg, 320, $ext);
				// crop the image to match the height of 128px (mobile)
				$service->resizeCrop($mobileImg, $mobileImg, 128, $ext);
			
				// move properties into backoffice
				$propName = "/".pathinfo($image)['filename'].".prop";
				copy(pathinfo($image)['dirname'].$propName, __DIR__."/images".$propName);				
			}			
		}
	}

?>