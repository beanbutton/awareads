<?php
/*
 * index.php
 */
?>
<?php get_header(); ?>
<!--Contents-->
	<!--Jquery meteor carousel--> 
	<?php if ( function_exists( 'meteor_slideshow' ) ) { meteor_slideshow(); } ?>
	<div id="mainContainer">
    <section id="content">
        <h2>About AwareAds</h2>
        <p><?php the_content(); ?></p>
    </section>

</div>
<?php get_footer(); ?>