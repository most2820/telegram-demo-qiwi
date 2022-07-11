<?php

declare(strict_types=1);

use SergiX44\Nutgram\Nutgram;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$nutgram = new Nutgram($_ENV['TELEGRAM_TOKEN']);

(require __DIR__ . '/config/container.php')($nutgram->getContainer());
(require __DIR__ . '/config/middleware.php')($nutgram);
(require __DIR__ . '/config/routes.php')($nutgram);
(require __DIR__ . '/config/migrations.php')($nutgram->getContainer());

return $nutgram;
