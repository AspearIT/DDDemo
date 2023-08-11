<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Entity;

use AspearIT\DDDemo\Domain\Order\Value\Price;
use Ramsey\Uuid\UuidInterface;

class OrderLine
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly UuidInterface $productId,
        private int                    $amount,
        private readonly Price         $pricePerUnit,
    ) {
        $this->amountShouldBeAtLeastOne($amount);
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProductId(): UuidInterface
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

    public function changeAmount(int $amount): void
    {
        $this->amountShouldBeAtLeastOne($amount);
        $this->amount = $amount;
    }

    private function amountShouldBeAtLeastOne(int $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount should be greater than 0');
        }
    }
}