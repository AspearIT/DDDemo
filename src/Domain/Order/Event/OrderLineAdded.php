<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Event;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Value\Price;

readonly class OrderLineAdded extends Event
{
    public function __construct(
        Uuid          $orderId,
        int           $version,
        private Uuid  $orderLineId,
        private Uuid  $productId,
        private int   $amount,
        private Price $pricePerUnit,
    ) {
        parent::__construct(Order::class, $orderId, $version);
    }

    public function getOrderLineId(): Uuid
    {
        return $this->orderLineId;
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