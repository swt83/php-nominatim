# Nominatum

A PHP library for working w/ the Nominatum API.

## Install

Normal install via Composer.

## Usage

```php
use Travis\Nominatum;

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
```

See the [API Guide](https://nominatim.org/release-docs/develop/api/Overview/) for additional methods.