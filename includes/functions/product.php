<?php

function snkpo_get_stock( $product_id ) {

    $stock = [
        'ok' => 0,
        'uns' => 0,
    ];

    $_stock = get_post_meta( $product_id, 'stock' );

    if ( $_stock ) :

        foreach ( $_stock as $key => $value ) :

            $stock['ok'] += $value['stock_ok'];
            $stock['uns'] += $value['stock_unschedule'];

        endforeach;

    endif;

    if ( $stock['ok'] < 0 ) :
        $stock['ok'] = 0;
    endif;

    if ( $stock['uns'] < 0 ) :
        $stock['uns'] = 0;
    endif;

    return $stock;

}