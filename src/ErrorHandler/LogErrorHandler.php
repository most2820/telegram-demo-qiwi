<?php

namespace App\ErrorHandler;

use Psr\Log\LoggerInterface;
use SergiX44\Nutgram\Nutgram;
use Throwable;

class LogErrorHandler
{
    private LoggerInterface $logger;

    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    public function __invoke(Nutgram $nutgram, Throwable $exception): void
    {
        print_r([
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage()
        ]);

        $this->logger->error($exception->getMessage(), [
            'exception' => $exception,
        ]);
    }
}