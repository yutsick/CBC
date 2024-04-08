<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Candidate;

class AllCandidatesGoals
{
    public function __invoke(): string
    {
        return wc_price( Candidate::get_all_goal_total() );
    }
}