<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Donation;
class CandidateProgress
{
    public function __invoke($atts)
    {
        $options = shortcode_atts(
            ['id' => get_the_ID()],
            $atts,
            'candidate_progress'
        );

        $goal = (int) get_post_meta($options['id'], '_goal', true);
        $donations = (int) Donation::get_candidate_total($options['id']);
        $amount_raised = get_post_meta($options['id'], '_amount_raised', true);

        if ($donations <= 0 || $goal <= 0) {
            return 0;
        }

        $remaining_amount = (int) do_shortcode('[candidate_remaining_amount]');

        if($remaining_amount === 0) {
            return 100;
        }

        return ($amount_raised / $goal) * 100;
    }
}