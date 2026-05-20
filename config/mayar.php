<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mayar API Key
    |--------------------------------------------------------------------------
    |
    | This value is the API key used to authenticate requests to the Mayar API.
    | You should define this in your environment file as MAYAR_API_KEY to avoid
    | hard-coding sensitive credentials in source control.
    |
    */
    'api_key' => env('MAYAR_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Mayar Mode
    |--------------------------------------------------------------------------
    |
    | This option controls which Mayar environment your application will use.
    | Supported values are typically "sandbox" for testing and "live" for
    | production transactions. The default is "sandbox" for safer local setup.
    |
    */
    'mode' => env('MAYAR_MODE', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | Mayar Webhook Receiver
    |--------------------------------------------------------------------------
    |
    | Enable the inbound webhook route provided by this package. You may keep
    | this disabled and manually register the controller in your own routes
    | file when you need full control over endpoint URL and middleware.
    |
    */
    'webhook' => [
        'enabled' => env('MAYAR_WEBHOOK_ROUTE_ENABLED', true),
        'path' => env('MAYAR_WEBHOOK_ROUTE_PATH', 'webhooks/mayar'),
        'middleware' => ['api'],
        'name' => 'mayar.webhook',
        'ignore_unknown_events' => env('MAYAR_WEBHOOK_IGNORE_UNKNOWN_EVENTS', true),
    ],

];
