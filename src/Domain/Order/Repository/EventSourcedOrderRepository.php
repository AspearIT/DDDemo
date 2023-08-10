<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Factory\OrderFactory;

readonly class EventSourcedOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private EventBus $eventBus,
        private EventStorage $eventStorage,
        private OrderFactory $orderFactory,
    ) {}

    public function getOrder(Uuid $orderId): Order
    {
        $events = $this->eventStorage->getEvents(Order::class, $orderId);
        return $this->orderFactory->reconstituteOrder($events);
    }

    public function save(Order $order): void
    {
        $newEvents = $order->popNewEvents();
        $this->eventStorage->storeEvents($newEvents);
        $this->eventBus->publish(...$newEvents);
    }
}