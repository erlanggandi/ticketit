<?php

return [

    'approach_threshold_minutes' => env('SLA_APPROACH_THRESHOLD_MINUTES', 60),

    'fallback_durations' => [
        'Critical' => 4,
        'High' => 8,
        'Medium' => 72,
        'Low' => 120,
    ],

];
