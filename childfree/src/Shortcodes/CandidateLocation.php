<?php

namespace WZ\ChildFree\Shortcodes;

class CandidateLocation
{
    public function __invoke()
    {
        global $post;
        $location = get_post_meta($post->ID, '_location', true);
        global $wpdb;
        $table_name = $wpdb->prefix . 'jet_cct_zipcodes';
        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE zipcode = %s", $location);
        $row = $wpdb->get_row($query);

        $map_marker = sprintf('%s, %s', $row->city, $row->state_code);

//        echo '<div class="distance"><p><span><i class="fas fa-map-marker-alt"></i> ' . $map_marker . '</span> </p></div>';
        echo '<div class="distance"><span>' . $map_marker . '</span></div>';
    }
}
