<?php

namespace WZ\ChildFree\Shortcodes;

use WZ\ChildFree\Models\Candidate;

class AllCandidatesRemainingAmount
{
    public function __invoke(): string
    {
        return wc_price( Candidate::get_all_remaining_total() );
    }
}