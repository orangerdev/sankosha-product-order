<div class="sankosha-list-product-wrapper">

    <?php
    global $post;

    $current_page = home_url( get_query_var('pagename') );

    $page = (get_query_var('page')) ? get_query_var('page') : 1;

    $args = [
        'post_type'      => 'snkpo-product',
        'posts_per_page' => 8,
        'paged'          => $page,
    ];

    if ( isset( $_GET['search'] ) ) :

        $args['s'] = $_GET['search'];

    endif;

    $products = new \WP_Query( $args );
 
    if ( $products->have_posts() ) :

        ?>

        <form class="sankosha-search" action="" method="get">
            <input type="text" name="search" value="<?php echo ( isset( $_GET['search'] ) ? $_GET['search'] : "" ); ?>">
            <button type="submit"><i class="dashicons dashicons-search"></i></button>
        </form>

        <div class="sankosha-list-product">

            <?php

            while ( $products->have_posts() ) :
                $products->the_post();

                ?>
                <div class="product">
                    <div class="product-content">
                        <figure>
                            <a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'>
                                <?php the_post_thumbnail(); ?>
                            </a>
                        </figure>
                        <h2>
                            <a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>'>
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        <?php 
                        if ( is_user_logged_in() ) : 
                            $price = carbon_get_the_post_meta('price');
                            if ( $price ) :
                                ?>
                                <div class="price">
                                    <?php echo snkpo_money_format( $price ); ?>
                                </div>
                                <?php 
                            endif; 
                        endif;    
                        ?>
                        <a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' class='button'>
                            <?php _e('Detail','snkpo'); ?>
                        </a>
                        <a href="<?php the_permalink(); ?>?act=order" title='<?php the_title_attribute(); ?>' class='button'>
                            <?php _e('Order','snkpo'); ?>
                        </a>
                    </div>
                </div>
                <?php

            endwhile;
            wp_reset_postdata();

            ?>

        </div>

        <?php

        if ( $products->max_num_pages > 1 ) :

            ?>
            <div class="sankosha-pagination">
            <?php

            if ( $products->max_num_pages > 5 ) :

                if ( $page > 2 ) :
                    if ( intval($products->max_num_pages-$page) <= 0 ) :
                        $min = 4;
                        $plus = 0;
                    elseif ( intval($products->max_num_pages-$page) === 1 ):
                        $min = 3;
                        $plus = 1;
                    else:
                        $min = 2;
                        $plus = 2;
                    endif;
                elseif ( intval($page) === 2 ):
                    $min = 1;
                    $plus = 3;
                elseif ( intval($page) === 1 ):
                    $min = 0;
                    $plus = 4;                    
                endif;

                $start_page = $page-$min;
                $end_page = $page+$plus;

            else:

                $start_page = 1;
                $end_page = $products->max_num_pages;

            endif;

            for ( $i=$start_page;  $i <= $end_page;  $i++ ) :

                ?>
                <a href="<?php echo $current_page.'/'.$i.'/'; ?>" class="<?php echo ( $i === $page ) ? "active" : ""; ?>"><?php echo $i; ?></a>
                <?php

            endfor;

            ?>
            </div>
            <?php

        endif;
    
    else:

        ?>
        <p><?php _e( 'No product data found.', 'snkpo' ); ?></p>
        <?php

    endif;

    ?>
</div>
