<?php

namespace WZ\ChildFree\Shortcodes;

class CandidatesCount
{
     public function __invoke($atts)
     {
         // get total products count
         $total_candidates = wp_count_posts('product')->publish;
            return $total_candidates;

     }

}