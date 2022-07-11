<?php

declare(strict_types=1);

namespace App\Entity\Payment;

use DateTimeImmutable;

final class Status
{
    private string $value;
    private DateTimeImmutable $changedDateTime;

    public function __construct(
        string $value,
        DateTimeImmutable $changedDateTime
    )
    {

        $this->value = $value;
        $this->changedDateTime = $changedDateTime;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getChangedDateTime(): DateTimeImmutable
    {
        return $this->changedDateTime;
    }
}
