<?php
declare(strict_types=1);

namespace Tests;

use AspearIT\DDDemo\Domain\Order\Entity\Order;
use AspearIT\DDDemo\Domain\Order\Entity\OrderLine;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAdded;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAmountChanged;
use AspearIT\DDDemo\Domain\Order\Exception\OrderLineNotFoundException;
use AspearIT\DDDemo\Domain\Order\Value\Price;
use AspearIT\DDDemo\Domain\Order\Value\Product;
use Mockery\MockInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OrderTest extends UnitTestCase
{
    public function testAddOrderLineShouldTriggerOrderLineAddedEvent()
    {
        $order = new Order(Uuid::uuid4(), 1, [
            $this->orderLine(),
            $this->orderLine(),
        ]);

        $order->addOrderLine(
            $this->product(),
            5
        );

        $this->assertCount(3, $order->getOrderLines());
        $this->assertEvents([
            OrderLineAdded::class => fn (OrderLineAdded $event) => $this->assertEquals(5, $event->getAmount()),
        ], $order->popNewEvents());
    }

    public function testAddOrderLineShouldTriggerOrderLineChangedWhenProductAlreadyAddedToOrder()
    {
        $product = $this->product();
        $existingOrderLine = $this->orderLine($product->getId(), 2);
        $existingOrderLine->shouldReceive('changeAmount')->once()->with(2 + 5);

        $order = new Order(Uuid::uuid4(), 1, [
            $this->orderLine(),
            $existingOrderLine,
        ]);

        $order->addOrderLine(
            $product,
            5
        );

        $this->assertCount(2, $order->getOrderLines());
        $this->assertEvents([
            OrderLineAmountChanged::class => fn (OrderLineAmountChanged $event) => $this->assertEquals(2 + 5, $event->getAmount()),
        ], $order->popNewEvents());
    }

    public function testChangeProductAmountShouldTriggerOrderLineAmountChangedEvent()
    {
        $orderLine = $this->orderLine(null, 2);
        $orderLine->shouldReceive('changeAmount')->once()->with(5);
        $order = new Order(Uuid::uuid4(), 1, [
            $orderLine,
        ]);

        $order->changeProductAmount(
            $orderLine->getId(),
            5
        );

        $this->assertCount(1, $order->getOrderLines());
        $this->assertEvents([
            OrderLineAmountChanged::class => fn  (OrderLineAmountChanged $event) => $this->assertEquals(5, $event->getAmount()),
        ], $order->popNewEvents());
    }

    public function testChangeProductAmountShouldThrowExceptionWhenOrderLineNotFound()
    {
        $order = new Order(Uuid::uuid4(), 1, [
            $this->orderLine(),
        ]);

        $this->expectException(OrderLineNotFoundException::class);
        $order->changeProductAmount(
            Uuid::uuid4(),
            5
        );
    }

    public function testChangeProductAmountShouldNotTriggerEventsWhenAmountIsNotDifferent()
    {
        $orderLine = $this->orderLine(null, 2);
        $orderLine->shouldNotReceive('changeAmount');
        $order = new Order(Uuid::uuid4(), 1, [
            $orderLine,
        ]);
        $order->changeProductAmount(
            $orderLine->getId(),
            2
        );
        $this->assertCount(0, $order->popNewEvents(), 'No events should be triggered when amount is not different');
    }

    private function product(): Product
    {
        return new Product(
            Uuid::uuid4(),
            new Price(200),
        );
    }

    private function orderLine(UuidInterface $productId = null, int $amount = 1): OrderLine&MockInterface
    {
        $productId = $productId ?? Uuid::uuid4();
        return \Mockery::mock(OrderLine::class, [
            'getId' => Uuid::uuid4(),
            'getProductId' => $productId,
            'getAmount' => $amount,
        ]);
    }
}