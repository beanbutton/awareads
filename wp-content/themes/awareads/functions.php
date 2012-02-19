<?php
/*
 * functions.php
 */

//removed admin bar and the space
add_filter( 'show_admin_bar', '__return_false' );

//added menu at Appearance
add_theme_support( 'menus' );

//Google Maps Shortcode
function do_googleMaps($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '400',
      "height" => '400',
      "src" => ''
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.$src.'&amp;output=embed" ></iframe>';
}
add_shortcode("googlemap", "do_googleMaps");


//short link button
function sButton($atts, $content = null) {
   extract(shortcode_atts(array('link' => '#'), $atts));
   return '<a class="button" href="'.$link.'"><span>' . do_shortcode($content) . '</span></a>';
}
add_shortcode('button', 'sButton');

//thumbnail image
add_theme_support( 'post-thumbnails', array( 'post' ) ); // Valid by post
set_post_thumbnail_size( 150, 150, true ); // size: 150 x 150px


?>