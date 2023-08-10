<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Factory;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Entity\OrderLine;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAdded;

class OrderFactory
{
    public function reconstituteOrder(array $events): Order
    {
        $replay = new EventReplay();
        $replay->on(OrderLineAdded::class, function (OrderLineAdded $event, array $data): array {
            $data['orderLines'][$event->getOrderLineId()->toString()] = [
                'id' => $event->getOrderLineId(),
                'product_id' => $event->getProductId(),
                'amount' => $event->getAmount(),
                'price_per_unit' => $event->getPricePerUnit(),
            ];
            return $data;
        });

        $data = $replay->replay($events, ['orderLines' => []]);
        $orderLines = [];
        foreach ($data['orderLines'] as $orderLine) {
            $orderLines[] = new OrderLine(
                $orderLine['id'],
                $orderLine['product_id'],
                $orderLine['amount'],
                $orderLine['price_per_unit'],
            );
        }
        return new Order($data['id'], $data['version'], $orderLines);
    }
}