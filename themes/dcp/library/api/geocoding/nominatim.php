<?php

namespace hacklabr\geocoding;

class Nominatim {

    private static function make_nominatim_request ($url) {
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
        $pretty_address = $address->road ?? '';
        if (!empty($address->house_number)) {
            $pretty_address .= ', ' . $address->house_number;
        }
        return $pretty_address;
    }

    static function geocode (string $address) {
        $viewbox = apply_filters('dcp_geocoding_viewbox', [-43.23, -22.87, -43.27, -22.91]);

        $params = [
            'q' => $address,
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'countrycodes' => 'br',
            'viewbox' => implode(',', $viewbox),
            'bounded' => '1',
        ];

        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query($params);
        $data = self::make_nominatim_request($url);

        if (is_array($data)) {
            foreach ($data as $match) {
                return [
                    'lat' => floatval($match->lat),
                    'lon' => floatval($match->lon),
                    'address' => self::get_pretty_address($match->address),
                    'full_address' => $match->display_name,
                ];
            }
        }

        return null;
    }

    static function reverse_geocode (float $lat, float $lon) {
        $params = [
            'lat' => $lat,
            'lon' => $lon,
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'accept-language' => 'pt-BR',
        ];

        $url = 'https://nominatim.openstreetmap.org/reverse?' . http_build_query($params);
        $match = self::make_nominatim_request($url);

        if (!empty($match) && empty($match->error)) {
            return [
                'lat' => floatval($match->lat),
                'lon' => floatval($match->lon),
                'address' => self::get_pretty_address($match->address),
                'full_address' => $match->display_name,
            ];
        }

        return null;
    }
}
