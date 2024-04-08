<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class HandleCalculateTotals extends Hook
{
    public static array $hooks = array( 'woocommerce_get_cart_contents' );

    public function __invoke( $cart_contents ) {
        foreach ( $cart_contents as $key => $item ) {
//            $cart_contents[ $key ]['data']->set_price( $item['donation'] );
            $item['data']->set_price( $item['donation'] );
        }

        return $cart_contents;
    }
}
