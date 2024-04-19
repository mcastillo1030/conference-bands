<?php

return [
    'global_team' => [
        'name' => 'Revival Movement',
        'personal_team' => false,
    ],
    'bracelet_statuses' => [
        'system',
        'reserved',
        'registered',
    ],
    'order_statuses' => [
        'pending',
        'complete',
        'n/a',
    ],
    'square' => [
        'item_name' => 'RMT Wristband Reservation',
        'bracelet_cost' => 25,
        'transaction_fee' => 0.029,
        'transaction_fee_fixed' => 0.30,
    ],
    'export_formats' => [
        'csv',
        'xlsx',
        // 'pdf', pdf export is not supported yet
    ],
];
