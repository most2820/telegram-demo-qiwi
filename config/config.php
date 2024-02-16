<?php

use Doctrine\Migrations;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;
use App\Console;

use function App\env;

return [
    'dev' => (bool)env("APP_DEBUG", '0'),
    'telegram_token' => env('TELEGRAM_TOKEN'),
    'qiwi_token' => env('QIWI_TOKEN'),
    'logger' => [
        'debug' => (bool)env("APP_DEBUG", '0'),
        'file' => dirname(__DIR__ ) . '/var/log/app.log',
        'stderr' => true,
    ],
    'doctrine' => [
        'dev_mode' => true,
        'cache_dir' => dirname(__DIR__) . '/var/cache/doctrine/cache',
        'proxy_dir' => dirname(__DIR__) . '/var/cache/doctrine/proxy',
        'connection' => [
            'driver' => env('DB_DRIVER'),
            'host' => env('DB_HOST'),
            'user' => env('DB_USER'),
            'password' => env('DB_PASSWORD'),
            'dbname' => env('DB_NAME'),
            'charset' => 'utf8mb4',
        ],
        'subscribers' => [],
        'metadata_dirs' => [
            dirname(__DIR__) . '/src/Entity',
        ],
    ],
    'console' => [
        'commands' => [
            ValidateSchemaCommand::class,

            Migrations\Tools\Console\Command\ExecuteCommand::class,
            Migrations\Tools\Console\Command\MigrateCommand::class,
            Migrations\Tools\Console\Command\LatestCommand::class,
            Migrations\Tools\Console\Command\ListCommand::class,
            Migrations\Tools\Console\Command\StatusCommand::class,
            Migrations\Tools\Console\Command\UpToDateCommand::class,
            Migrations\Tools\Console\Command\GenerateCommand::class,
            Migrations\Tools\Console\Command\DiffCommand::class,

            Console\RunCommand::class,
            Console\FixtureLoadCommand::class
        ],
        'fixture_paths' => [
            dirname(__DIR__) . '/src/Data/Fixture'
        ]
    ],
];
