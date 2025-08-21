<?php

namespace hacklabr\geocoding;

class GoogleMaps {

    public static function get_google_maps_key () {
        return get_option('google_maps_key') ?: getenv('GOOGLE_MAPS_API_KEY') ?: '';
    }

    private static function make_google_maps_request ($url) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; NossasDCP/1.0)',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        if ($response === false || $http_code !== 200) {
            $error_message = $curl_error ?: "HTTP Error: {$http_code}";
            do_action('logger', $error_message, 'warning');
            return null;
        }

        return json_decode($response);
    }

    private static function get_pretty_address (object $address) {
        $road = array_find($address->address_components, fn($comp) => in_array('route', $comp->types));
        $house_number = array_find($address->address_components, fn($comp) => in_array('street_number', $comp->types));

        $pretty_address = $road->long_name ?? '';
        if (!empty($house_number)) {
            $pretty_address .= ', ' . $house_number->long_name;
        }
        return $pretty_address;
    }

    static function geocode (string $address) {
        [$west, $north, $east, $south] = apply_filters('dcp_geocoding_viewbox', [-43.23, -22.87, -43.27, -22.91]);

        $params = [
            'address' => $address,
            'key' => self::get_google_maps_key(),
            'bounds' => $south . ',' . $west . '|' . $north . ',' . $east,
            'language' => 'pt-BR',
        ];

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($params);
        $data = self::make_google_maps_request($url);

        if (empty($data) || $data->status !== 'OK') {
            return null;
        }

        if (is_array($data->results)) {
            foreach ($data->results as $match) {
                return [
                    'lat' => $match->geometry->location->lat,
                    'lon' => $match->geometry->location->lng,
                    'address' => self::get_pretty_address($match),
                    'full_address' => $match->formatted_address,
                ];
            }
        }

        return null;
    }

    static function reverse_geocode (float $lat, float $lon) {
        $params = [
            'latlng' => $lat . ',' . $lon,
            'key' => self::get_google_maps_key(),
            'language' => 'pt-BR',
            'result_type' => 'street_address',
        ];

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query($params);
        $data = self::make_google_maps_request($url);

        if (empty($data) || $data->status !== 'OK') {
            return null;
        }

        if (is_array($data->results)) {
            foreach ($data->results as $match) {
                return [
                    'lat' => $match->geometry->location->lat,
                    'lon' => $match->geometry->location->lng,
                    'address' => self::get_pretty_address($match),
                    'full_address' => $match->formatted_address,
                ];
            }
        }

        return null;
    }
}
