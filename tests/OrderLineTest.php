<?php
declare(strict_types=1);

namespace Tests;

use AspearIT\DDDemo\Domain\Order\Entity\OrderLine;
use AspearIT\DDDemo\Domain\Order\Value\Price;
use Ramsey\Uuid\Uuid;

class OrderLineTest extends UnitTestCase
{
    public function testChangeAmount()
    {
        $orderLine = new OrderLine(
            Uuid::uuid4(),
            Uuid::uuid4(),
            1,
            new Price(3)
        );

        $orderLine->changeAmount(5);

        $this->assertEquals(5, $orderLine->getAmount());
    }

    public function testOrderLineCannotBeCreatedWithNegativeAmount()
    {
        $this->expectException(\InvalidArgumentException::class);

        new OrderLine(
            Uuid::uuid4(),
            Uuid::uuid4(),
            -1,
            new Price(3)
        );
    }

    public function testChangeAmountShouldThrowExceptionWhenAmountIsNegative()
    {
        $this->expectException(\InvalidArgumentException::class);

        $orderLine = new OrderLine(
            Uuid::uuid4(),
            Uuid::uuid4(),
            1,
            new Price(3)
        );

        $orderLine->changeAmount(0);
    }
}