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
        <?php the_content(); ?>
    </section>

</div>
<?php get_footer(); ?>