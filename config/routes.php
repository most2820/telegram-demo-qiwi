<?php

declare(strict_types=1);

use App\Callback\PayCallback;
use App\Command\StartCommand;
use SergiX44\Nutgram\Nutgram;

return function (Nutgram $nutgram) {
    $nutgram->onCommand('start', StartCommand::class);
    $nutgram->onCallbackQueryData(PayCallback::CALLBACK_QIWI_PAY, [PayCallback::class, PayCallback::CALLBACK_QIWI_PAY]);
    $nutgram->onCallbackQueryData(sprintf('%d {param}', PayCallback::CALLBACK_QIWI_CHECK), [PayCallback::class, PayCallback::CALLBACK_QIWI_CHECK]);
    $nutgram->onText("([0-9]+)", [PayCallback::class, PayCallback::CALLBACK_QIWI_BILL]);
};
