<?php
/*
 * index.php
 */
?>
<?php get_header(); ?>
<!--Contents-->
<div id="mainContainer">
    <section id="hero">
        <!--<img src="<?php bloginfo('template_url'); ?>/img/placeholder.jpg" width="860" height="325">-->
        <?php if ( function_exists( 'meteor_slideshow' ) ) { meteor_slideshow(); } ?>

    </section>
    <section id="shadow"></section>
    <section id="content">
        <h2>About AwareAds</h2>
        <p><?php the_content(); ?></p>
    </section>

</div>
<?php get_footer(); ?>