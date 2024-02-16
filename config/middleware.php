<?php

declare(strict_types=1);

use App\Middleware\ChatChecker;
use SergiX44\Nutgram\Nutgram;

return function (Nutgram $nutgram) {
    $nutgram->middleware(ChatChecker::class);
};
