<!doctype html>  

<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	
	<head>
	<link rel="icon" href="<?php echo get_template_directory_uri() ?>/assets/img/favico.ico" />
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script> 
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        
        
        
        
        
        
        
        
        
        
<link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/bootstrap-3.1.1.min.css" type="text/css">
     <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/bootstrap-multiselect.css" type="text/css">
      <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/css/prettify.css" type="text/css">
		
		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/jquery-2.1.0.min.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/bootstrap-multiselect.js"></script>
		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/bootstrap-3.1.1.min.js"></script>
		
		<script type="text/javascript" src="<?php echo get_template_directory_uri() ?>/assets/js/prettify.js"></script>


        
        
        
        
        
        
        
        

		 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title><?php wp_title( '|', true, 'right' ); ?></title>
				
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
				
		<!-- media-queries.js (fallback) -->
		 
		<!--[if lt IE 9] --> 
			<!--<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>	-->	<!-- commented out 24/02/2014 - WG -->	
		<!-- [endif]-->

		<!-- html5.js -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
  		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->

		<!-- theme options from options panel -->






		<!-- typeahead plugin - if top nav search bar enabled -->

	</head>
	<?php 

			
	?>
	<body <?php body_class($class); ?> >
		<div class="container-fluid wrapper">				
		    <header class="row-fluid content-wrapper">
				<div class="row-fluid  header-inner contactOBM">
					<form id="obmform" class="form-inline span7 row-fluid form OBM" name="obm" action="submit_action.php" method="post">
						<!-- <span class="book-now span2 row-fluid">Book Now</span> -->
						<div class="input-prepend span3 row-fluid arrival">
							<label for="dateofarrival" class="add-on span5">Arrive</label>
							<input id="datepicker" name="dateofarrival" type="text" value="<?php $today = date("d/m/Y", strtotime('today'));echo $today;?>" class="span7"/>
						</div>
						<div class="input-prepend span3 row-fluid departure">
							<label for="dateofdeparture" class="add-on span5">Depart</label>
							<input id="datepicker2" name="dateofdeparture" type="text" value="<?php $today = date("d/m/Y", strtotime('tomorrow'));echo $today;?>" class="span7"/>

						</div>
						<div class="input-prepend span3 row-fluid guests">
							<label for="numberofguests" class="add-on span7">Guests</label>
							<select id="numberofguests" name="numberofguests" class="span5">
								<option value="1" selected="selected">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
							</select>
						</div>
						<div class="span2 row-fluid">
							<input type="submit" value="Book Now"  class="btn btn-default span12"/>
						</div>
					</form>
					<div class="span5 contact-details">
						<span>+44 (0) 207 341 5800  |  <a title="The Harrington Contact Form" href="http://www.theharrington.com/contact-us">reservations@theharrington.com</a>  |  <a title="Click here to see the map" href="http://www.theharrington.com/contact-us">SW7 4JJ</a></span>
					</div>

				</div>

				<div class="row-fluid header-inner  mainNav">
					<nav class="span10">
						<?php wp_nav_menu(array( 
							'menu' => 'Main Menu', /* menu name */
							'menu_class' => 'nav nav-pills',
							'theme_location' => 'main_nav', /* where in the theme it's assigned */
							'container' => 'false', /* container class */
							'fallback_cb' => 'bones_main_nav_fallback', /* menu fallback */
							'depth' => '1'
							
							,
							//'walker' => new Bootstrap_Walker() 
							)); // Adjust using Menus in Wordpress Admin ?>
					</nav>
					<div class="social-icons">
						<a href="https://www.facebook.com/TheHarringtonApartments" target="_blank"><img src="http://cdn.theharrington.com/fb-icon.png" alt="visit our Facebook page" width="32" height="32"/></a>
						<a href="https://twitter.com/TheHarrington_" target="_blank"><img src="http://cdn.theharrington.com/tw-icon.png" alt="visit our Twitter page" width="32" height="32"/></a>
						<a href="https://plus.google.com/+Theharrington" target="_blank"><img src="http://cdn.theharrington.com/gp-icon.png" alt="visit our Google Plus page" width="32" height="32"/></a>
					</div>
				</div>
			</header>

		
		<section class="row-fluid content-wrapper ">
			<section class="row-fluid content-inner">