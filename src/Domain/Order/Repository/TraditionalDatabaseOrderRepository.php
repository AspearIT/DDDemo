<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Entity\OrderLine;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAdded;

class TraditionalDatabaseOrderRepository implements OrderRepositoryInterface
{
    public function getOrder(Uuid $orderId): Order
    {
        $order = Order::with('orderLines')->findOrFail($orderId);
        return new Order(
            Uuid::fromString($order->id),
            $order->version,
            $order->orderLines->map(function (\Model\OrderLine $orderLine) {
                return new OrderLine(
                    Uuid::fromString($orderLine->id),
                    Uuid::fromString($orderLine->product_id),
                    $orderLine->amount,
                    new Price($orderLine->price_per_unit),
                );
            })->toArray(),
        );
    }

    public function save(Order $order): void
    {
        $events = $order->popNewEvents();
        foreach ($events as $event) {
            if ($event instanceof OrderLineAdded) {
                $this->saveAddedOrderLine($event);
            } else {
                throw new \RuntimeException('Unknown event type ' . get_class($event));
            }
        }
    }

    private function saveAddedOrderLine(OrderLineAdded $event): void
    {
        $orderLine = new \Model\OrderLine();
        $orderLine->id = $event->getOrderLineId()->toString();
        $orderLine->product_id = $event->getProductId()->toString();
        $orderLine->amount = $event->getAmount();
        $orderLine->price_per_unit = $event->getPricePerUnit()->getAmount();
        $orderLine->order_id = $event->getOrderId()->toString();
        $orderLine->save();

        $order = Order::findOrFail($event->getOrderId());
        //The version can be used to prevent simultaneous requests messing up the order domain.
        $order->version = $event->getVersion();
        $order->saveOrFail();
    }
}