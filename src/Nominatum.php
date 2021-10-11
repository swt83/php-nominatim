<?php

namespace Travis;

class Nominatum
{
    public static function to_coords($query, $timeout = 30)
    {
        return static::request('https://nominatim.openstreetmap.org/search?q='.urlencode($query).'&format=json', $timeout);
    }

    public static function to_address($lat, $lon, $timeout = 30)
    {
        return static::request('https://nominatim.openstreetmap.org/reverse?lat='.urlencode($lat).'&lon='.urlencode($lon).'&format=json', $timeout);
    }

    protected static function request($url, $timeout)
    {
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => [
                    'User-Agent: PHP'
                ]
            ]
        ];
        $context = stream_context_create($opts);
        $content = file_get_contents($url, false, $context);

        return json_decode($content);
    }
}