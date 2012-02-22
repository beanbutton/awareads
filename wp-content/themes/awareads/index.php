<?php
/*
 * index.php
 */
?>
<?php get_header(); ?>
<!--Contents-->

    <div id="mainContainer">
      <!--start-->
      
      <h3 class="title">Web Properties</h3>
       <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="post">
                 
                <?php the_content(); ?>
                
                </div><!-- /.post -->

            <?php endwhile; ?>

                <div class="nav-below">
                    <span class="nav-previous"><?php next_posts_link('Old Page') ?></span>
                    <span class="nav-next"><?php previous_posts_link('New Page') ?></span>
                </div><!-- /.nav-below -->

            <?php else : ?>

                <h2 class="title">Sorry, couldn't find Page</h2>
                
            <?php endif; ?>
                   
                  

      <!--end-->
    </div><!--.mainContainer-->
<?php get_footer(); ?>