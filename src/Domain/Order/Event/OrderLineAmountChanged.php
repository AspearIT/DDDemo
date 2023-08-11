<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Event;

use AspearIT\DDDemo\Domain\Event;
use AspearIT\DDDemo\Domain\Order\Entity\Order;
use Ramsey\Uuid\UuidInterface;

readonly class OrderLineAmountChanged extends Event
{
    public function __construct(
        UuidInterface $orderId,
        int $version,
        private UuidInterface $lineId,
        private int $amount,
    ) {
        parent::__construct(Order::class, $orderId, $version);
    }

    public function getLineId(): UuidInterface
    {
        return $this->lineId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}