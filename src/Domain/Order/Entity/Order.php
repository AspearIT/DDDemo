<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Entity;

use AspearIT\DDDemo\Domain\Order\Event\OrderLineAdded;

class Order extends AggregateRoot
{
    /**
     * @param OrderLine[] $orderLines
     */
    public function __construct(
        private readonly Uuid $id,
        private int $version,
        private array $orderLines,
    ) {}

    public function addOrderLine(Product $product, int $amount): Uuid
    {
        $newId = Uuid::v4();
        $this->orderLines[] = new OrderLine($newId, $product->getId(), $amount, $product->getPrice());
        $this->event(new OrderLineAdded(
            $this->id,
            ++$this->version,
            $newId,
            $product->getId(),
            $amount,
            $product->getPrice(),
        ));
        return $newId;
    }
}