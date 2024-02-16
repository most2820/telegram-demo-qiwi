<?php

declare(strict_types=1);

namespace App\Middleware;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Attributes\ChatType;
use function App\env;

class ChatChecker
{
    public function __invoke(Nutgram $nutgram, $next): void
    {
        if ($nutgram->chat()->type == ChatType::PRIVATE) {
            $next($nutgram);
        }
    }

}