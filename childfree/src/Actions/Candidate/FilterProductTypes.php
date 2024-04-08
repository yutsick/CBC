<?php

namespace WZ\ChildFree\Actions\Candidate;

use WZ\ChildFree\Actions\Hook;

class FilterProductTypes extends Hook
{
    public static array $hooks = [
        'product_type_selector'
    ];

    public function __invoke() {
        return [
            'candidate' => __('Candidate'),
            'general-donation' => __('General Donation'),
            'expansion-donation' => __('Expansion Donation'),
            'location-donation' => __('Specific Location Donation'),
            'fund-all-candidates' => __('Fund All Candidates'),
        ];
    }
}
