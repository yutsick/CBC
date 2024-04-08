<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class HandleAddCartItem extends Hook
{
    public static array $hooks = ['woocommerce_add_cart_item'];

    public function __invoke( $item ) {
        $item['data']->set_price( $item['donation'] );

        return $item;
    }
}
