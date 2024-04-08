<?php

namespace WZ\ChildFree\Shortcodes;

class CandidateGoalAmount
{
    public function __invoke() {
        global $wpdb;
        $candidate_id = get_the_ID();
        $goal = $wpdb->get_var("SELECT meta_value FROM wp_postmeta WHERE post_id = $candidate_id AND meta_key = '_goal'");
        return wc_price( (int) $goal );
    }
}