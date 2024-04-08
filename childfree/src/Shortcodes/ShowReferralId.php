<?php

namespace WZ\ChildFree\Shortcodes;

class ShowReferralId
{
    public function __invoke() {
        return $_COOKIE['candidate_referrer'];
    }
}
