#!/usr/bin/env php
<?php

declare(strict_types=1);

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use SergiX44\Nutgram\Nutgram;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

/** @var Nutgram $nutgram */
$nutgram = require_once dirname(__DIR__) . '/autoload.php';

$commands = $nutgram->getContainer()->get('config')['doctrine']['console']['commands'];

$cli = new Application('Console');

$entityManager = $nutgram->getContainer()->get(EntityManagerInterface::class);

$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');

foreach ($commands as $name) {
    print_r($name . PHP_EOL);
    $cli->add($nutgram->getContainer()->get($name));
}

$cli->run();
