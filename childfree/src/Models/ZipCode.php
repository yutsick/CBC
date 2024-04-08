<?php

namespace WZ\ChildFree\Models;

class ZipCode
{
    public static function find($zipcode)
    {
        global $wpdb;

        $row = $wpdb->get_row(
            $wpdb->prepare(
                "
				SELECT *
				FROM wp_jet_cct_zipcodes
				WHERE zipcode = %s
				",
                $zipcode
            ),
            ARRAY_A
        );

        $coordinates = self::find_google_lat_lng($zipcode);

        if ($coordinates) {
            $row['latitude'] = $coordinates['latitude'];
            $row['longitude'] = $coordinates['longitude'];
        }

        return (object)$row;
    }

    public static function find_google_lat_lng($zip_code)
    {
        $api_key = 'AIzaSyB5Dc2DupDK2lz6m0J3TJglvixt8gnqXSE';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=components=postal_code:" . $zip_code . "%20USA&sensor=false&key=" . $api_key;

        // Make the API request using wp_remote_get
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            // Handle the error if the request fails
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if ($data->status === 'OK') {
            $latitude = $data->results[0]->geometry->viewport->southwest->lat;
            $longitude = $data->results[0]->geometry->viewport->southwest->lng;
            return array('latitude' => $latitude, 'longitude' => $longitude);
        } else {
            return false;
        }
    }

}
