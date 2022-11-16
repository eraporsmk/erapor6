<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'paths' => resource_path('views'),
    'mapbox' => [
        'access_token' => env('MAPS_MAPBOX_ACCESS_TOKEN', null),
    ],
    'google_maps' => [
        'access_token' => env('MAPS_GOOGLE_MAPS_ACCESS_TOKEN', null)
    ],
    'map_center' => [
        //'lat' => '-6.153397354108362', 
        //'lng' => '106.62622013919741'
        'lat' => '-6.175232396817502',
        'lng' => '106.82713133951854',
    ],
];
