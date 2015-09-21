<?php

?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>iOS7 Switch</title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0;">

    <!-- <link rel="stylesheet" href="view/ios7/css/wuzzle.css" type="text/css" media="screen" charset="utf-8"> -->
    <link rel="stylesheet" href="view/ios7/css/build.css" type="text/css" media="screen" charset="utf-8">
        <style type="text/css" media="screen">
	@import url(http://fonts.googleapis.com/css?family=Roboto:700,300,400);
	@import url(http://fonts.googleapis.com/css?family=Roboto:700);
        html, body {
		/**/
		background: #fff; 
		margin: 0; padding: 0; height: 100%; 
		position: relative;
		font-family: monospace; 
		text-align: center;
		color: hsl(0, 0%, 40%);}
        .ios-switch{
          	position: relative;
          	top: 20%;
          	margin: 0 auto;
        	}
	h1 { font-family: 'Roboto', sans-serif; font-weight: 700; font-size: 36px; margin-top: 100px; color:#222;}
        footer { position: fixed; bottom: 0; width: 100%; text-align: center; height:50px;background-image:url(view/ios7/css/squairy_light/squairy_light.png);}
        footer small{ margin-top: 20px; margin-bottom: 0px; font-size: .9em; display: block;  color: hsl(0, 0%, 40%); }
        footer small a{ text-decoration: none; font-weight: bold; color: hsl(0, 0%, 20%); }
    </style>
  </head>
  <body>

	<h1>Cloud Industry SafeMode</h1>




  <input type="checkbox" name="switch" />











  <footer>
    <small>to <a href="#" target="_blank">set</a> / go to <a href="dashboard.php">dashboard</a></small>
  </footer>


<!-- </div>
</div> -->
  <!----- container ------>


  <script src="view/ios7/js/build.js"></script>
  <script type="text/javascript" charset="utf-8">

      var Switch = require('ios7-switch')
        , checkbox = document.querySelector('input')
        , mySwitch = new Switch(checkbox);

      mySwitch.el.addEventListener('click', function(e){
        e.preventDefault();
        mySwitch.toggle();
      }, false)


  </script>
  </body>
</html>
