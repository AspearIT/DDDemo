<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use Ramsey\Uuid\UuidInterface;

interface OrderRepositoryInterface
{
    public function getOrder(UuidInterface $orderId): Order;

    public function save(Order $order): void;
}