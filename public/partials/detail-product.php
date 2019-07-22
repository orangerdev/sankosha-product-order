<div class="product-meta">
    
    <?php
    if ( is_user_logged_in() ) : 
        $price = carbon_get_post_meta( get_the_ID(), 'price' );
        if ( $price ) :
            ?>
            <p><span class="label">Price:</span> <?php echo snkpo_money_format( $price ); ?></p>
            <?php
        endif;
    endif; 

    $location = carbon_get_post_meta( get_the_ID(), 'location' );
    if ( $location ) :
        ?>
        <p><span class="label">Location:</span> <?php echo $location; ?></p>
        <?php
    endif;

    ?>
</div>
<?php
$gallery = carbon_get_post_meta( get_the_ID(), 'gallery' );
if ( $gallery ) :

    ?>
    <div class="product-galley">
        <div class="owl-carousel owl-theme">
        <?php

        foreach ( $gallery as $key => $value ) :

            $image = wp_get_attachment_image_src( $value, 'full' );
            
            if ( $image ) :

                ?>
                <div class="item">
                    <a href="<?php echo $image[0]; ?>" class="thickbox" rel="gallery-plants">
                        <?php echo wp_get_attachment_image( $value ); ?>
                    </a>
                </div>
                <?php

            endif;

        endforeach;

        ?>
        </div>
    </div>
    <?php

endif;
?>
<div class="product-btn">
    <button class="button button-primary order-product-modal-btn"><?php _e( 'ORDER NOW', 'snkpo' ); ?></button>
</div>