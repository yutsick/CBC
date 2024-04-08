<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;
use WZ\ChildFree\Models\Candidate;
use WZ\ChildFree\Models\ExpansionDonation;
use WZ\ChildFree\Models\GeneralDonation;
use WZ\ChildFree\Models\LocationDonation;
use WZ\ChildFree\Models\FundAllCandidates;

class FilterProductClass extends Hook
{
    public static array $hooks = ['woocommerce_product_class'];

    public static int $arguments = 2;

    public function __invoke( $class, $type ) {
        if ( 'general-donation' === $type ) {
            return GeneralDonation::class;
        }
        if ( 'expansion-donation' === $type ) {
            return ExpansionDonation::class;
        }
        if ( 'location-donation' === $type ) {
            return LocationDonation::class;
        }
        if ( 'fund-all-candidates' === $type ) {
            return FundAllCandidates::class;
        }

        return Candidate::class;
    }
}
