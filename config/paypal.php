<?php

// 以前のエラー回避のため、クラス定数（use Srmklive\PayPal\Enums\Modeなど）は使用しません。

return [
    // 環境設定 (.env の PAYPAL_MODE を参照し、デフォルトは 'sandbox')
    'mode'    => env('PAYPAL_MODE', 'sandbox'), 
    'locale'  => env('PAYPAL_LOCALE', 'en_US'),

    'sandbox' => [
        // PayPal Developerで取得したクライアントIDとシークレットを .env に設定
        'client_id'         => env('PAYPAL_SANDBOX_CLIENT_ID', ''), 
        'client_secret'     => env('PAYPAL_SANDBOX_SECRET', ''),    
        // この値は通常固定
        'app_id'            => 'APP-80W284485P519543T', 
    ],

    'live' => [
        'client_id'         => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret'     => env('PAYPAL_LIVE_SECRET', ''),
        'app_id'            => '',
    ],

    // 通貨設定 (.env の PAYPAL_API_CURRENCY を参照し、デフォルトは 'USD')
    'currency' => env('PAYPAL_API_CURRENCY', 'USD'), 
    'api_timeout' => 30,
];
