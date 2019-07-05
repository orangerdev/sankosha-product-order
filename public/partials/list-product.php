<div class="sankosha-list-product">
<?php
    global $post;
    $products = get_posts([
        'post_type'   => 'snkpo-product',
        'numberposts' => -1,
    ]);

    foreach((array) $products as $post) :
        setup_postdata($post);
    ?>
    <div class="product">
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
        <a href="<?php the_permalink(); ?>" title='<?php the_title_attribute(); ?>' class='button'>
            <?php _e('Pesan','snkpo'); ?>
        </a>
    </div>
    <?php
    endforeach;
    wp_reset_postdata();

?>
</div>
