<?php

namespace WZ\ChildFree\Shortcodes;

class CandidateRemainingAmount
{
    /*
     * Usage: [candidate_remaining_amount id="123" display="formatted"]
     * Possible values for id: any valid candidate ID
     * Possible values for display: simple, formatted
     * */
    public function __invoke($atts) {
        $atts = shortcode_atts(
            array(
                'id' => get_the_ID(), // Default to the current post ID if not specified
                'display'      => 'simple',     // Default value if no parameter is passed with shortcode
            ),
            $atts,
            'candidate_remaining_amount'
        );

        // Check if WooCommerce is active
        if (class_exists('WooCommerce')) {
            global $wpdb;

            // Check if goal and amount raised values exist
            $goal = get_post_meta($atts['id'], '_goal', true);
//            $amount_raised = get_post_meta($atts['id'], '_amount_raised', true);
            $amount_raised = get_post_meta($atts['id'], '_amount_raised', true);

            if ($goal) {

                if ($amount_raised === '') {
                    $amount_raised = 0;
                }

                $remaining_amount = $goal - $amount_raised;

                if ($remaining_amount <= 0) {
                    return 'Completed';
                }

                $output = array(
                    'simple'    => (int) $remaining_amount,
                    'formatted' => wc_price((int) $remaining_amount),
                );

                // Return the requested value based on the attribute
                return $atts['display'] === 'formatted' ? $output['formatted'] : $output['simple'];
            }

            return '$0';
        }

        return 'WooCommerce is not active.';
    }
}
