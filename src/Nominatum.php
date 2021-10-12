<?php

namespace Travis;

class Nominatum
{
    public static function calc_point_grid($lat1, $lon1, $lat2, $lon2, $unit = 'km', $radius = 1, $is_to_geojson = false)
    {
        // logic taken from https://github.com/Turfjs/turf/blob/master/packages/turf-point-grid/index.ts

        // init
        $coords = [];

        $north = $lat2;
        $south = $lat1;
        $east = $lon2;
        $west = $lon1;

        $x_fraction = $radius / static::calc_distance($south, $west, $south, $east, $unit);
        $cell_width = $x_fraction * ($east - $west);
        $y_fraction = $radius / static::calc_distance($south, $west, $north, $west, $unit);
        $cell_height = $y_fraction * ($north - $south);

        $bbox_width = $east - $west;
        $bbox_height = $north - $south;
        $columns = floor($bbox_width / $cell_width);
        $rows = floor($bbox_height / $cell_height);

        // adjust origin of the grid
        $delta_x = ($bbox_width - $columns * $cell_width) / 2;
        $delta_y = ($bbox_height - $rows * $cell_height) / 2;

        $current_x = $west + $delta_x;
        while ($current_x <= $east)
        {
            $current_y = $south + $delta_y;
            while ($current_y <= $north)
            {
                $coords[] = [
                    'lat' => $current_y,
                    'lon' => $current_x,
                ];

                $current_y += $cell_height;
            }
            $current_x += $cell_width;
        }

        // if to geojson (debugging purposes)...
        // throw this output into geojson.io and see what the grid looks like!
        if ($is_to_geojson)
        {
            $string = '{"type": "FeatureCollection","features":[';
            foreach ($coords as $coord)
            {
                $string .= '{';
                    $string .= '"type": "Feature",';
                    $string .= '"geometry": {';
                        $string .= '"type": "Point",';
                        $string .= '"coordinates": ['.$coord['lon'].', '.$coord['lat'].']';
                    $string .= '},';
                    $string .= '"properties": {';
                        $string .= '"prop0": "value0"';
                    $string .= '}';
                $string .= '},';
            }
            $string = substr($string, 0, -1);
            $string .= ']}';

            return $string;
        }

        // return
        return $coords;
    }

    public static function calc_area($lat1, $lon1, $lat2, $lon2, $unit = 'km')
    {
        $r = static::units($unit);

        // lat = north / south
        // long = east / west
        // lat1, lon1 = bottom left
        // lat1, lon2 = bottom right
        // lat2, lon1 = top left
        // lat2, lon2 = top right

        // calc the area
        $distance_x = static::calc_distance($lat1, $lon1, $lat1, $lon2, $unit);
        $distance_y = static::calc_distance($lat1, $lon1, $lat2, $lon1, $unit);

        // return
        return $distance_x * $distance_y;
    }

    public static function calc_distance($lat1, $lon1, $lat2, $lon2, $unit = 'kilometer')
    {
        $r = static::units($unit);

        // convert to radians
        $lat1 = deg2rad($lat1);
        $lat2 = deg2rad($lat2);
        $lon1 = deg2rad($lon1);
        $lon2 = deg2rad($lon2);

        // return
        return acos(sin($lat1) * sin($lat2) +
                    cos($lat1) * cos($lat2) *
                    cos($lon1 - $lon2)) * $r['radius'];
    }

    protected static function units($string)
    {
        // units of measure
        switch ($string)
        {
            case 'mile':
                $r = 3959;  // earth's radius in miles
            break;
            case 'miles':
                $r = 3959;  // earth's radius in miles
            break;
            case 'nautical mile';
                $r = 3440;  // earth's radius in nautical miles
            break;
            case 'nautical miles';
                $r = 3440;  // earth's radius in nautical miles
            break;
            default:
                $string = 'kilometer';
                $r = 6371;  // earth's radius in km
                break;
        }

        return [
            'unit' => $string,
            'radius' => $r,
        ];
    }

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