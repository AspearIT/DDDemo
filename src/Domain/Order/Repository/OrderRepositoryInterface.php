<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\Order\Entity\Order;

interface OrderRepositoryInterface
{
    public function getOrder(Uuid $orderId): Order;

    public function save(Order $order): void;
}