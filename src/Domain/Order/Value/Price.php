<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Value;

readonly class Price
{
    public function __construct(private int $cents) {}

    public function getPriceInCents(): int
    {
        return $this->cents;
    }

    public function getPrice(): float
    {
        return $this->cents / 100;
    }
}