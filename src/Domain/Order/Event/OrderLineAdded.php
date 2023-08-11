<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Event;

use AspearIT\DDDemo\Domain\Event;
use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Value\Price;
use Ramsey\Uuid\UuidInterface;

readonly class OrderLineAdded extends Event
{
    public function __construct(
        UuidInterface          $orderId,
        int           $version,
        private UuidInterface  $orderLineId,
        private UuidInterface  $productId,
        private int   $amount,
        private Price $pricePerUnit,
    ) {
        parent::__construct(Order::class, $orderId, $version);
    }

    public function getOrderLineId(): UuidInterface
    {
        return $this->orderLineId;
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
}