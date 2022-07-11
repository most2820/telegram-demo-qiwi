<?php

use Doctrine\Migrations;
use Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand;

return [
    'doctrine' => [
        'dev_mode' => true,
        'cache_dir' => dirname(__DIR__) . '/cache/doctrine/cache',
        'proxy_dir' => dirname(__DIR__) . '/cache/doctrine/proxy',
        'connection' => [
            'driver' => $_ENV['DB_DRIVER'],
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'dbname' => $_ENV['DB_NAME'],
            'charset' => 'utf8mb4',
        ],
        'subscribers' => [],
        'metadata_dirs' => [
            dirname(__DIR__) . '/src/Entity',
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
            ],
        ],

    ],
];
