<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>2048</title>

  <link href="style/main.css" rel="stylesheet" type="text/css">
  <link rel="shortcut icon" href="favicon.ico">
  <link rel="apple-touch-icon" href="meta/apple-touch-icon.png">
  <link rel="apple-touch-startup-image" href="meta/apple-touch-startup-image-640x1096.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"> <!-- iPhone 5+ -->
  <link rel="apple-touch-startup-image" href="meta/apple-touch-startup-image-640x920.png"  media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)"> <!-- iPhone, retina -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, target-densitydpi=160dpi, initial-scale=1.0, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<body>
  <?php
  if(isset($_GET['sel']))
  {
    $selection = $_GET['sel'];
  }
  else{
    $selection = "all time";
  }

  $db = new PDO("mysql:host=localhost;dbname=twothousandfourtyeight","root","");
  $res = $db->query("SELECT * FROM reisen");
  foreach ($res as $r) {
    }

  ?>
	<div class="scoreContainer">
		<h1 class="title">
		  Scores for <?php echo $selection ?>
		</h1>
    <div class="container">
      <p>Ricaurus: 200</p>
    </div>
	</div>
</body>
</html>