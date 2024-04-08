<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Donation;
class CandidateSeeking
{
    public function __invoke($atts)
    {
        // get product id
        $candidate_Id = get_the_ID();
        $result = get_post_meta($candidate_Id, '_sex', true) == "male" ?
    "<div class=\"candidate-badge male\">Seeking Vasectomy</div>" :
    "<div class=\"candidate-badge female\">Seeking Tubal Ligation</div>";

        

        return $result;
    }

}