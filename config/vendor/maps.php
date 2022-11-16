<?php

return [

    /*
     |----------------------------------------------------------------------
     | Default Map Service Name
     |----------------------------------------------------------------------
     |
     | Here you may specify which of the map services below you wish to use
     | as your default service for all displayed maps. Of course you may use many services at once using the Maps library.
     | Available maps: 'google', 'osm', 'bing', 'mapquest', 'yandex', 'mapkit'
     |
     */

    'default' => env('MAPS_SERVICE', 'osm'),

    /*
    |--------------------------------------------------------------------------
    | Map Services
    |--------------------------------------------------------------------------
    |
    | Here are each of the map services setup for your application.
    | Of course, examples of configuring each map api that is supported by
    | Maps is shown below to make development simple.
    |
    |
    | All proprietary map services require an API Key, so make sure you have
    | the key for your particular service of choice defined in your .env
    | before you begin development.
    |
    */

    'services' => [

        'google' => [
            // https://developers.google.com/maps/documentation/javascript/get-api-key
            // https://developers.google.com/maps/documentation/embed/get-api-key
            'key' => env('MAPS_GOOGLE_KEY', ''),

            // https://developers.google.com/maps/documentation/javascript/maptypes
            'type' => 'roadmap', // 'roadmap', 'satellite', 'hybrid', 'terrain',
        ],

        'bing' => [
            // https://msdn.microsoft.com/en-us/library/ff428642.aspx
            // https://www.bingmapsportal.com
            'key' => env('MAPS_BING_KEY', ''),
        ],

        'osm' => [
            'type' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        ],

        'yandex' => [
            // https://developer.tech.yandex.com/keys
            // limited free quota
            'key' => env('MAPS_YANDEX_KEY', ''),
        ],

        'mapquest' => [
            // https://developer.mapquest.com/plan_purchase/steps/business_edition/business_edition_free/register
            // https://developer.mapquest.com/user/me/apps
            'key' => env('MAPS_MAPQUEST_KEY', ''),

            // https://developer.mapquest.com/documentation/mapquest-js/v1.3/l-mapquest-tile-layer/
            'type' => 'map', // 'map', 'hybrid', 'satellite', 'light', 'dark'
        ],

        'mapkit' => [
            // https://developer.apple.com/videos/play/wwdc2018/508
            // https://developer.apple.com/documentation/mapkitjs/setting_up_mapkit_js?changes=latest_minor
            'key' => env('MAPS_MAPKIT_KEY', ''),

            // https://developer.apple.com/documentation/mapkitjs/mapkit/map/maptypes
            'type' => 'standard', // 'standard', 'hybrid', 'satellite'
        ],

    ],

    /*
     |--------------------------------------------------------------------------
     | Maps Enabled
     |--------------------------------------------------------------------------
     |
     | By default, Maps is enabled. You can set the value to false to disable
     | rendering of all maps.
     |
     */

    'enabled' => env('MAPS_ENABLED', true),

];
