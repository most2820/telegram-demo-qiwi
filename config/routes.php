<?php

declare(strict_types=1);

use App\Callback\PayCallback;
use App\Command\StartCommand;
use SergiX44\Nutgram\Nutgram;

return function (Nutgram $nutgram) {
    $nutgram->onCommand('start', StartCommand::class);
    $nutgram->onCallbackQueryData('pay', PayCallback::class);
    $nutgram->onCallbackQueryData('check {param}', [PayCallback::class, 'check']);
    $nutgram->onText("([0-9]+)", [PayCallback::class, 'bill']);
    $nutgram->onException(function (Nutgram $nutgram, \Throwable $exception) {
        $nutgram->sendMessage($exception->getMessage());
    });
};
