<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Value;

readonly class Product
{
    /**
     * Despite the fact that this object has a uuid it's not gonna change in this domain. So it's a value object.
     * Changes to the product should be handled in a different domain.
     */
    public function __construct(
        private Uuid $id,
        private Price $price,
    ) {}

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}