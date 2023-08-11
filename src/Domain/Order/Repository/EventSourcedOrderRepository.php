<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\EventBusInterface;
use AspearIT\DDDemo\Domain\EventStorageInterface;
use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Factory\OrderFactory;
use Ramsey\Uuid\UuidInterface;

readonly class EventSourcedOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private EventBusInterface $eventBus,
        private EventStorageInterface $eventStorage,
        private OrderFactory $orderFactory,
    ) {}

    public function getOrder(UuidInterface $orderId): Order
    {
        $events = $this->eventStorage->getEvents(Order::class, $orderId);
        return $this->orderFactory->reconstituteOrder($events);
    }

    public function save(Order $order): void
    {
        $newEvents = $order->popNewEvents();
        $this->eventStorage->saveEvents(...$newEvents);
        $this->eventBus->publish(...$newEvents);
    }
}