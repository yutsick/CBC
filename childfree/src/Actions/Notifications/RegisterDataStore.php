<?php

namespace WZ\ChildFree\Actions\Notifications;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\DataStores\NotificationDataStore;

class RegisterDataStore extends Hook
{
    public static array $hooks = ['woocommerce_data_stores'];

    public function __invoke( $data_stores ) {
        $data_stores['notification'] = NotificationDataStore::class;

        return $data_stores;
    }
}
