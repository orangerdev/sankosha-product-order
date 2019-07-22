<?php

get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post(); 
        ?>
        <div class="single-product">
            <h2 class="single-product-title"><?php the_title(); ?></h2>
            <?php 
            the_content();
            ?>
        </div>
        <?php
    endwhile;
endif;

get_footer();