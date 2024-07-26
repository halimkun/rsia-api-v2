<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

$monthlyBaseConfig = [
    'driver'    => 'monolog',
    'handler'   => \App\Logging\CustomRotatingFileHandler::class,
    'with'      => [
        'maxFiles'       => 0,
        'level'          => \Monolog\Logger::DEBUG,
        'bubble'         => true,
        'filePermission' => 0664,
        'useLocking'     => false,
        'filenameFormat' => '{filename}-{date}',
        'dateFormat'     => \App\Logging\CustomRotatingFileHandler::FILE_PER_MONTH,
    ],
];

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

    'deprecations' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),

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
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],


        // =============== CUSTOM CHANNELS
        'monthly' => $monthlyBaseConfig,

        // Custom channels
        'berkas' => array_merge($monthlyBaseConfig, [
            'path'  => storage_path('logs/berkas.log'),
            'level' => env('LOG_LEVEL_BERKAS', 'debug'),
            'with'  => [
                'filename' => storage_path('logs/berkas.log'),
            ],
        ]),

        'undangan' => array_merge($monthlyBaseConfig, [
            'path'  => storage_path('logs/undangan.log'),
            'level' => env('LOG_LEVEL_UNDANGAN', 'debug'),
            'with'  => [
                'filename' => storage_path('logs/undangan.log'),
            ],
        ]),

        'kehadiran' => array_merge($monthlyBaseConfig, [
            'path'  => storage_path('logs/kehadiran.log'),
            'level' => env('LOG_LEVEL_kehadiran', 'debug'),
            'with'  => [
                'filename' => storage_path('logs/kehadiran.log'),
            ],
        ]),

        'notifications' => array_merge($monthlyBaseConfig, [
            'path'  => storage_path('logs/fcm.log'),
            'level' => env('LOG_LEVEL_FCM', 'debug'),
            'with'  => [
                'filename' => storage_path('logs/fcm.log'),
            ],
        ]),
    ],

];
