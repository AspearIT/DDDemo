<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Repository;

use AspearIT\DDDemo\Domain\Order\Value\Product;
use Ramsey\Uuid\UuidInterface;

interface ProductRepositoryInterface
{
    public function getProduct(UuidInterface $id): Product;
}