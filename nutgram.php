<?php

declare(strict_types=1);

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;

/** @var Nutgram $nutgram */
$nutgram = require_once __DIR__ . '/autoload.php';

$nutgram->registerMyCommands();
$nutgram->setRunningMode(Polling::class);
$nutgram->run();
