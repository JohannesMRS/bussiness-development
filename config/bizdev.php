<?php

return [
    'admin_whatsapp' => env('BIZDEV_ADMIN_WHATSAPP') ?: '6280000000000',
    'bank_name' => env('BIZDEV_BANK_NAME') ?: 'BANK DEMO — JANGAN TRANSFER',
    'bank_account_name' => env('BIZDEV_BANK_ACCOUNT_NAME') ?: 'BIZDEV HMPS MI (DATA DUMMY)',
    'bank_account_number' => env('BIZDEV_BANK_ACCOUNT_NUMBER') ?: '0000000000',
    'is_demo_contact' => env('BIZDEV_ADMIN_WHATSAPP') === null
        || env('BIZDEV_ADMIN_WHATSAPP') === ''
        || env('BIZDEV_ADMIN_WHATSAPP') === '6280000000000',
    'is_demo_bank' => env('BIZDEV_BANK_NAME') === null
        || env('BIZDEV_BANK_NAME') === ''
        || env('BIZDEV_BANK_ACCOUNT_NUMBER') === null
        || env('BIZDEV_BANK_ACCOUNT_NUMBER') === ''
        || env('BIZDEV_BANK_ACCOUNT_NUMBER') === '0000000000',
];
