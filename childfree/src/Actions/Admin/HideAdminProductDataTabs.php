<?php

namespace WZ\ChildFree\Actions\Admin;

use WZ\ChildFree\Actions\Hook;

class HideAdminProductDataTabs extends Hook
{
    public static array $hooks = ['woocommerce_product_data_tabs'];

    public static int $priority = 100;

    public function __invoke( $tabs ) {
        unset($tabs['inventory']);
        unset($tabs['shipping']);
        unset($tabs['linked_product']);
        unset($tabs['attribute']);
        unset($tabs['variations']);
        unset($tabs['advanced']);
        unset($tabs['marketplace-suggestions']);

        return $tabs;
    }
}
