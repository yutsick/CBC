<?php

namespace WZ\ChildFree\Actions\Donations;

use Wz\Childfree\Actions\Hook;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\FundAllCandidates;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;

class HandleDonationProductsAccess extends Hook
{
    public static array $hooks = [
        'init',
    ];

    public function __invoke()
    {
        add_action('template_redirect', function() {
            $current_user = wp_get_current_user();
            $current_page_id = get_queried_object_id();
            $restricted_page_ids = array(
                GeneralDonation::PRODUCT_ID,
                LocationDonation::PRODUCT_ID,
                ExpansionDonation::PRODUCT_ID,
                FundAllCandidates::PRODUCT_ID,
            );
            $redirect_page_id = 14;

            if (in_array('administrator', (array) $current_user->roles)) {
                return;
            }

            if (in_array($current_page_id, $restricted_page_ids) && !is_admin() && !isset($_GET['post']) && !is_numeric($_GET['post'])) {
                wp_redirect(get_permalink($redirect_page_id));
                exit;
            }
        });
    }
}
