<?php

namespace WZ\ChildFree\Models;

class Funding
{
    /**
     * Get the goal amount per the candidate's age and sex
     *
     * @param int $age
     * @param string $sex
     * @return \stdClass|null
     */
    public static function get(int $age, string $sex)
    {
        global $wpdb;

        if ($age > 40) {
            $age = 40;
        }

        return $wpdb->get_row(
            $wpdb->prepare(
                "
				SELECT *
				FROM wp_jet_cct_donation_amounts
				WHERE age = %s
				AND sex = %s
				",
                $age,
                $sex
            )
        );
    }
}
