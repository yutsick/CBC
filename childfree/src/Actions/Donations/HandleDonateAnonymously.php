<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;

class HandleDonateAnonymously extends Hook
{

    public static array $hooks = array(
        'woocommerce_checkout_update_order_meta',
    );

    public function __invoke($order_id): void
    {
        // Check if donate_anonymously is set in $_POST
        if (isset($_POST['donate_anonymously'])) {
            update_post_meta($order_id, '_donate_anonymously', $_POST['donate_anonymously']);
        }
    }
}
