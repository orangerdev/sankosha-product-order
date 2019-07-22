<?php

function snkpo_money_format( $number ) {

    $number = 'Rp '.number_format( $number, 2, ",", "." );

    return $number;

}