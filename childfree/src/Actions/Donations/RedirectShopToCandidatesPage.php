<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;

class RedirectShopToCandidatesPage extends Hook
{

        public static array $hooks = array(
            'template_redirect'
        );

        public function __invoke(): void
        {
            if( is_shop() ){
                wp_redirect( home_url( '/candidates/' ) ); // Assign custom internal page here
                exit();
            }
        }
}
