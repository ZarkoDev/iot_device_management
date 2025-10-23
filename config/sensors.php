<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Temperature Thresholds
    |--------------------------------------------------------------------------
    |
    | These values define the normal operating temperature range for sensors.
    | Readings outside this range will trigger alerts.
    |
    */

    'temperature' => [
        'min' => env('SENSOR_MIN_TEMPERATURE', 0),
        'max' => env('SENSOR_MAX_TEMPERATURE', 30),
    ],
];
