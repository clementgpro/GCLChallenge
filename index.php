<?php

include 'backoffice/upload/ServiceUtils.class.php';
require_once 'Mobile-Detect-2.8.11/Mobile_Detect.php';


$detect = new Mobile_Detect();
$pathInImages = "desktop";
// Any mobile device (phones or tablets).
if ( $detect->isMobile() ) {
  $pathInImages = "mobile";
}

$service = new ServiceUtils();

$imagesOfServer = $service->list_images("../gclcimages/");
if (sizeof($imagesOfServer)) {

  foreach ($imagesOfServer as $image) {
    // treat only file under 8mo
    if (filesize($image) <= 8388608) {
      $ext = pathinfo($image)['extension'];
      $destkopImg = "backoffice/images/desktop/".pathinfo($image)['filename']."_desktop.".$ext;
      $mobileImg = "backoffice/images/mobile/".pathinfo($image)['filename']."_mobile.".$ext;

      // service the image to match a width of 900px (desktop)
      $service->resizeWithProportion($image, $destkopImg, 900, $ext);
      // crop the image to match the height of 360px (desktop)
      $service->resizeCrop($destkopImg, $destkopImg, 360, $ext);

      // resize the image to match a width of 320px (mobile)
      $service->resizeWithProportion($image, $mobileImg, 320, $ext);
      // crop the image to match the height of 128px (mobile)
      $service->resizeCrop($mobileImg, $mobileImg, 128, $ext);

      // remove uploaded file
      unlink($image);
    } else {
      // copy image without resizing
      copy($image, "backoffice/images/desktop/".pathinfo($image)['filename']."_desktop.".pathinfo($image)['extension']);
      copy($image, "backoffice/images/mobile/".pathinfo($image)['filename']."_mobile.".pathinfo($image)['extension']);
      unlink($image);
    }
  }
}

$images = $service->list_images("backoffice/images/".$pathInImages."/");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="MakeItGreen official GCLC webpage">
  <meta name="author" content="Green Code Lab Challenge">

  <title>Green Code Lab Challenge 2014 sample webpage</title>

  <!-- Bootstrap core CSS  and custom CSS -->
  <link href="css/mini_style.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">

  <!-- Scripts -->
  <!-- We've included jQuery. But you can of course use any other lib! -->
  <!--script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.mi.js"></script-->
  <script src="js/jquery-1.9.1.min.js"></script>
  <script src="js/jssor.slider.mini.js"></script>
  <script>
  jQuery(document).ready(function ($) {
    //Define an array of slideshow transition code
    var _SlideshowTransitions = [
    {$Duration:1200,x:0.3,$During:{$Left:[0.3,0.7]},$Easing:{$Left:$JssorEasing$.$EaseInCubic,$Opacity:$JssorEasing$.$EaseLinear},$Opacity:2 },
    {$Duration:1500,x:0.5,$Cols:2,$ChessMode:{$Column:3},$Easing:{$Left:$JssorEasing$.$EaseInOutCubic},$Opacity:2,$Brother:{$Duration:1500,$Opacity:2}}];
    //There is no caption plays random transition
    var _CaptionTransitions = [];

    var options = {
      $ArrowNavigatorOptions: {
        $Class: $JssorArrowNavigator$,
        $ChanceToShow: 2
      },
      $SlideshowOptions: {
        $Class: $JssorSlideshowRunner$,
        $Transitions: _SlideshowTransitions,
        $TransitionsOrder: 1,
        $ShowLink: true
      },
      $CaptionSliderOptions: {
        $Class: $JssorCaptionSlider$,
        $CaptionTransitions: _CaptionTransitions,
        $PlayInMode: 1,
        $PlayOutMode: 3
      },
      $BulletNavigatorOptions: {
        $Class: $JssorBulletNavigator$,                 //[Required] Class to create navigator instance
        $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
        $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
        $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
        $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
        $SpacingX: 10,                                  //[Optional] Horizontal space between each item in pixel, default value is 0
        $SpacingY: 10,                                  //[Optional] Vertical space between each item in pixel, default value is 0
        $Orientation: 1
      },
      //Avance automatiquement les images
      $AutoPlay: true,
      //3 secondes entre chaque image par défaut
      //Pause sur touche ou mousehover
      $PauseOnHover: 3
    };
    var jssor_slider1 = new $JssorSlider$('slider1_container', options);

    //Suite du JS
    //Listeners des boutons pause et play
    var pauseButton = document.getElementById("pause");
    var playButton = document.getElementById("play");

    pauseButton.addEventListener("click", function() {
      jssor_slider1.$Pause();
      playButton.style.display = "block";
      pauseButton.style.display = "none";
    });

    playButton.addEventListener("click", function() {
      jssor_slider1.$Play();
      playButton.style.display = "none";
      pauseButton.style.display = "block";
    });
  });
</script>
</head>
<body>
  <nav class="navbar glc-navbar navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <img src="img/logo_optimized.png" width="62" height="47" style="float: left;" />
        <a class="navbar-brand">GreenCodeLab Challenge 2014</a>
      </div>
    </div>
  </nav>


  <div class="jumbotron">

    <div class="container">
      <h1>Hello, world!</h1>
      <p>Welcome on board! This is your sample website. Fell free to add / correct what you want.
        <p>Please do not delete any sentence included in this webpage</p>
      </div>
    </div>

    <div class="container">
      <div class="row">

        <div id="slider1_container" class="alignh">
          <!-- controls -->
          <span id="pause" class="pausebutton"></span>
          <span id="play" class="playbutton"></span>
          <span u="arrowleft" class="jssora01l"></span>
          <span u="arrowright" class="jssora01r"></span>



          <div u="slides" class="slides">
            <!-- Slides Container -->
            <?php
              $slideshow = "";
              foreach ($images as $image) {
                $slideshow .= '<div><img u="image" src='.$image.' width="900" height="360" />
                <div u="caption" class="captionBlack">
                Ceci est une caption en dur
                </div>
                </div>';
              }
              echo $slideshow;
            ?>

          <!-- navigators -->
          <div u="navigator" class="jssorb11" id="navigation">
            <!-- bullet navigator item prototype -->
            <div u="prototype" style="POSITION: absolute; WIDTH: 25px; HEIGHT: 25px;"></div>
          </div>
        </div>
      </div>

      <div class="row" style="height:30px"> <!-- Place pour les bullets + numéro image--></div>
      <div class="row">
        <div class="col-md-4">
          <h2>Who will be the best Green developer in this latest edition ? </h2>
          <p>
            Eco-design software, is new. Your mission in the month before the contest
            is to make a short video explaining to a general public the impact of the software. you then publish
            this video on the networks. The most viral video will be rewarded and will bring you points in the final standings.
          </p>
        </div>
        <div class="col-md-4">
          <h2>On your marks, Ready, Set, CODE !</h2>

          <p>The Green Code Lab Challenge is a unique opportunity for you to to develop your skills set in ECO-CONCEPTION.<br>
            Come and share the adventure.</p>
            <ul>
              <li>Think eco design</li>
              <li>Think performance</li>
              <li>Think accessibility</li>
            </ul>
          </div>

          <div class="col-md-4">
            <h2>What's hot on Twitter?</h2>
            <a class="twitter-timeline" href="https://twitter.com/hashtag/GCLChallenge" data-widget-id="537334197569200128">Tweets sur #GCLChallenge</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            <h4 style="text-align:center"><div class="well">Share your thoughts!</div></h4>
          </div>
        </div>

        <hr>

        <footer>
          <p>Sincerely yours, <a href="http://www.greencodelab-challenge.org" target="_blank">The GreenCodeLab Challenge Team.</a></p>
        </footer>
      </div>
    </body>
    </html>
