<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
*/

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
   |--------------------------------------------------------------------------
   | Log Channels
   |--------------------------------------------------------------------------
   |
   | Here you may configure the log channels for your application. Out of
   | the box, Laravel uses the Monolog PHP logging library. This gives
   | you a variety of powerful log handlers / formatters to utilize.
   |
   | Available Drivers: "single", "daily", "slack", "syslog",
   |                    "errorlog", "monolog",
   |                    "custom", "stack"
   |
   */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            // 'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 3,
            'replace_placeholders' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => LOG_USER,
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],
        'export-product-tiktokshop' => [
            'driver' => 'daily',
            'path' => storage_path('logs/export-product-tiktokshop.log'),
            'days' => 3,
        ],
        'renew-flashdeal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/renew-flashdeal.log'),
            'days' => 3,
        ],
        'add-flashdeal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/add-flashdeal.log'),
            'days' => 3,
        ],
        'renew-flash-deal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/renew-flash-deal.log'),
            'days' => 3,
        ],
        'reup-product-flash-deal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/reup-product-flash-deal.log'),
            'days' => 3,
        ],
        'webhook' => [
            'driver' => 'daily',
            'path' => storage_path('logs/webhook.log'),
            'days' => 3,
        ],
        'product-to-flashdeal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/product-to-flashdeal.log'),
            'days' => 3,
        ],
        'autoflashdeals' => [
            'driver' => 'daily',
            'path' => storage_path('logs/autoflashdeals.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
        'report-order' => [
            'driver' => 'daily',
            'path' => storage_path('logs/report-order.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
        'telegram-wh' => [
            'driver' => 'daily',
            'path' => storage_path('logs/telegram-wh.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
        'convert-flashdeal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/convert-flashdeal.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
        'convert-flashdeal-save' => [
            'driver' => 'daily',
            'path' => storage_path('logs/convert-flashdeal-save.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
        'sync-flashdeal' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sync-flashdeal.log'),
            'level' => 'info', // Adjust log level as needed
            'days' => 3,
        ],
    ],

];
