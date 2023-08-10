<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Entity;

class OrderLine
{
    public function __construct(
        private readonly Uuid $uuid,
        private readonly Uuid $productId,
        private int $amount,
        private readonly Price $pricePerUnit,
    ) {
        if ($this->amount <= 0) {
            throw new \InvalidArgumentException('Amount should be greater than 0');
        }
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getProductId(): Uuid
    {
        return $this->productId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPricePerUnit(): Price
    {
        return $this->pricePerUnit;
    }
}