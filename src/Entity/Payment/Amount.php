<?php

declare(strict_types=1);

namespace App\Entity\Payment;

final class Amount
{
    const CURRENCY_RUB = "RUB";

    private string $currency;
    private int $value;

    public function __construct(
        string $currency,
        int    $value,
    )
    {
        $this->currency = $currency;
        $this->value = $value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
