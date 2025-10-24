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
        'critical_min' => env('SENSOR_CRITICAL_MIN_TEMPERATURE', -10),
        'critical_max' => env('SENSOR_CRITICAL_MAX_TEMPERATURE', 45),
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert Configuration
    |--------------------------------------------------------------------------
    |
    | Additional sensor-related configuration can be added here.
    |
    */

    'alert_timeout_minutes' => env('SENSOR_ALERT_TIMEOUT', 15),
];
