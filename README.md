# Nominatim

A PHP library for working w/ the Nominatim API.

## Install

Normal install via Composer.

## Usage

```php
use Travis\Nominatum;

// convert an address into lat/lon
$results = Nominatum::to_coords('United States Capitol, Washington, DC');
/*
Array
(
    [0] => stdClass Object
        (
            [place_id] => 106954421
            [licence] => Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright
            [osm_type] => way
            [osm_id] => 66418809
            [boundingbox] => Array
                (
                    [0] => 38.8887824
                    [1] => 38.8908427
                    [2] => -77.009574
                    [3] => -77.0083279
                )

            [lat] => 38.88981295000001
            [lon] => -77.00902077737487
            [display_name] => United States Capitol, East Front Plaza, Washington, District of Columbia, 20534, United States
            [class] => tourism
            [type] => attraction
            [importance] => 0.95363176772583
            [icon] => https://nominatim.openstreetmap.org/ui/mapicons//poi_point_of_interest.p.20.png
        )

)
*/

// convert lat/lon into an address
$results = Nominatum::to_address(38.88981295000001, -77.00902077737487);
/*
stdClass Object
(
    [place_id] => 106954400
    [licence] => Data © OpenStreetMap contributors, ODbL 1.0. https://osm.org/copyright
    [osm_type] => way
    [osm_id] => 66418809
    [lat] => 38.88981295000001
    [lon] => -77.00902077737487
    [display_name] => United States Capitol, East Front Plaza, Washington, District of Columbia, 20534, United States
    [address] => stdClass Object
        (
            [office] => United States Capitol
            [road] => East Front Plaza
            [city] => Washington
            [state] => District of Columbia
            [postcode] => 20534
            [country] => United States
            [country_code] => us
        )

    [boundingbox] => Array
        (
            [0] => 38.8887824
            [1] => 38.8908427
            [2] => -77.009574
            [3] => -77.0083279
        )

)
*/

// find the area of various lat/lon coordinates
$city = Nominatum::to_coords('Washington, DC');
$results = Nominatum::calc_area(ex($city, '0.boundingbox.0'), ex($city, '0.boundingbox.2'), ex($city, '0.boundingbox.1'), ex($city, '0.boundingbox.3'), 'miles');
/*
160.01340324464
*/

// find the distance between two lat/lon coordinates
$city1 = Nominatum::to_coords('Washington, DC');
$city2 = Nominatum::to_coords('Williamsburg, VA');
$results = Nominatum::calc_distance(ex($city1, '0.lat'), ex($city1, '0.lon'), ex($city2, '0.lat'), ex($city2, '0.lon'), 'miles');
/*
113.64097836938
*/

// produce a grid of points within the bounding box
$city = Nominatum::to_coords('Washington, DC');
$results = Nominatum::calc_point_grid(ex($city, '0.boundingbox.0'), ex($city, '0.boundingbox.2'), ex($city, '0.boundingbox.1'), ex($city, '0.boundingbox.3'), 'miles', 1);
/*
This will produce an array of coordinates that blanket the city, equally
distanced apart.  This has some niche uses, particularly with other APIs.
This code can also produce JSON that you can test on GeoJson.io.
*/
```

See the [API Guide](https://nominatim.org/release-docs/develop/api/Overview/) for additional methods.

## References

- [Geolocation](https://github.com/anthonymartin/GeoLocation-PHP) - Distance logic.
- [TurfJS](https://github.com/Turfjs/turf/blob/master/packages/turf-point-grid/index.ts) - Point grid logic.