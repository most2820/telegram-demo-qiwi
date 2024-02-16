<?php

declare(strict_types=1);

use App\ErrorHandler\LogErrorHandler;
use SergiX44\Nutgram\Nutgram;

use function App\env;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$nutgram = new Nutgram(env('TELEGRAM_TOKEN'));

(require __DIR__ . '/config/dependencies.php')($nutgram->getContainer());
(require __DIR__ . '/config/middleware.php')($nutgram);
(require __DIR__ . '/config/routes.php')($nutgram);

$nutgram->onApiError(LogErrorHandler::class);
$nutgram->onException(LogErrorHandler::class);

return $nutgram;
