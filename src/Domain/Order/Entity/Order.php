<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Entity;

use AspearIT\DDDemo\Domain\AggregateRoot;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAdded;
use AspearIT\DDDemo\Domain\Order\Event\OrderLineAmountChanged;
use AspearIT\DDDemo\Domain\Order\Exception\OrderLineNotFoundException;
use AspearIT\DDDemo\Domain\Order\Value\Product;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Order extends AggregateRoot
{
    /**
     * @param OrderLine[] $orderLines
     */
    public function __construct(
        private readonly UuidInterface $id,
        private int $version,
        private array $orderLines,
    ) {}

    /**
     * @return OrderLine[]
     */
    public function getOrderLines(): array
    {
        return $this->orderLines;
    }

    public function addOrderLine(Product $product, int $amount): void
    {
        try {
            $existingOrderLineForThisProduct = $this->getOrderLineFromProductId($product->getId());
            // Product already added, increase amount
            $this->changeProductAmount($existingOrderLineForThisProduct->getId(), $existingOrderLineForThisProduct->getAmount() + $amount);
            return;
        } catch (OrderLineNotFoundException) {}

        $newId = Uuid::uuid4();
        $this->orderLines[] = new OrderLine($newId, $product->getId(), $amount, $product->getPrice());
        $this->event(new OrderLineAdded(
            $this->id,
            ++$this->version,
            $newId,
            $product->getId(),
            $amount,
            $product->getPrice(),
        ));
    }

    public function changeProductAmount(UuidInterface $lineId, int $amount): void
    {
        $orderLine = $this->getOrderLine($lineId);
        if ($orderLine->getAmount() === $amount) {
            return;
        }
        $orderLine->changeAmount($amount);

        $this->event(new OrderLineAmountChanged(
            $this->id,
            ++$this->version,
            $lineId,
            $amount,
        ));
    }

    private function getOrderLine(UuidInterface $id): OrderLine
    {
        foreach ($this->orderLines as $orderLine) {
            if ($orderLine->getId()->equals($id)) {
                return $orderLine;
            }
        }
        throw new OrderLineNotFoundException("No order line found with id {$id}");
    }

    public function getOrderLineFromProductId(UuidInterface $productId): OrderLine
    {
        foreach ($this->orderLines as $orderLine) {
            if ($orderLine->getProductId()->equals($productId)) {
                return $orderLine;
            }
        }
        throw new OrderLineNotFoundException("No order line found with product id {$productId}");
    }

}