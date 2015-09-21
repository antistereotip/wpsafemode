<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WP Safemode</title>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="assets/css/foundation.css" />

<link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="assets/js/vendor/modernizr.js"></script>
    <link rel="icon" type="image/ico" href="favicon.ico" />
  </head>
  <body>

  <nav class="top-bar" data-topbar role="navigation" id="main-navigation">
  <div class="row">
  <div class="columns medium-6 small-12">
<!--  <div class="row">
  	<div class="columns medium-7 large-10 small-12">-->
  <ul class="title-area">
    <li class="name">
      <h1><a href="http://wpsafemode.com/" target="_blank" class="safemode-logo" title="Free WordPress administration tool" >Wordpress SafeMode</a></h1>
    </li>
     <li class="version" >
  <p class="small left">v0.04 beta</p>
   </li>
  </ul>
  </div>
<!--	<div class="columns medium-5 large-2 small-12 version text-left">
		
	</div>
	
	
  </div></div>-->
    
  <div class="columns medium-6 small-12 support-menu">
    <div class="row">
	   
		<div class="columns  medium-9  small-6 text-right">
			<p class="small version"><a href="http://wpsafemode.com/send-us-an-idea/" target="_blank">Send us an Idea</a></p>
		</div>
		<div class="columns  medium-3  small-6">
				<p class="small version"><a href="http://wpsafemode.com/support/" target="_blank">Support</a></p>
		</div>
		
	</div>	   

	</div>
  </div>

  </nav>
  <div class="row hidden-for-large-up">
  	<div class="small-12 columns  dropdown-menu text-center">
  		<a href="#" class="button small menu" data-dropdown="drop">MENU</a>
<ul id="drop" class="small f-dropdown" data-dropdown-content>
 <?php include('menu.php'); ?>
</ul>
  	</div>
  </div>
<div class="row" id="main-content">
	<div class="columns large-3 sidemenu-holder">
	<!--<div class="fill-blank columns large-12  visible-for-large-up"></div>-->
	<div class="visible-for-large-up">
<div id="main-side-nav" class="panel">
<ul class="custom-side-nav" >
  <li class=""> <h3 class="title">Dashboard</h3> </li>
   <?php include('menu.php'); ?>

</ul>
  </div>
  </div>
  </div>
  	<div class="columns large-9 main-column">

  	<?php if(isset($data['message'])): ?>
  	 <div class="row">
  	   <div class="columns large-12">
  	     <div class="alert-box [radius round]" data-alert>
  		<?php echo $data['message'] ?><a href="#" class="close">&times;</a>
     	</div>
  	   </div>
  	 </div>
  	<?php endif; ?>
  	
