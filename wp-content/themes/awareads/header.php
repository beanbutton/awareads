<?php

/*
 * header.php
 */
?>

<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!-- Consider adding a manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <!-- Use the .htaccess and remove these lines to avoid edge case issues.
             More info: h5bp.com/i/378 -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>
        <meta name="description" content="">

        <!-- Mobile viewport optimized: h5bp.com/viewport -->
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
        <!-- Favicon, Thumbnail image -->
        <link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/img/favicon.ico">
        <!-- External files -->
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>">

        <!-- More ideas for your <head> here: h5bp.com/d/head-Tips -->

        <!-- All JavaScript at the bottom, except this Modernizr build.
             Modernizr enables HTML5 elements & feature detects for optimal performance.
             Create your own custom Modernizr build: www.modernizr.com/download/ -->
        <script src="<?php bloginfo('template_url'); ?>/js/libs/modernizr-2.5.2.min.js"></script>
    </head>
    <body <?php body_class(); ?>>
        <div class="dot1"></div>
        <div class="dot2"></div>

        <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you support IE 6.
             chromium.org/developers/how-tos/chrome-frame-getting-started -->
        <!--[if lt IE 7]><p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
		
        <div id="headerWrapper">
        	<div id="nav1"><a href="index.php"><div id="logo"></div></a></div>
        	<div id="nav2">
        		<nav id="access" class="fl" role="navigation">
                  	<?php wp_nav_menu(); ?>
          	   </nav>
            </div>
        	<div id="nav3"><a href="linked.php"><div id="linkedin"></div></a></div>
        	<?php wp_head(); ?> 
            <!--header-->
        </div>

