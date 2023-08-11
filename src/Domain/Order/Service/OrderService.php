<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Service;

use AspearIT\DDDemo\Domain\Order\Repository\OrderRepositoryInterface;
use AspearIT\DDDemo\Domain\Order\Repository\ProductRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

readonly class OrderService
{
    public function __construct (
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function addOrderLine(UuidInterface $orderId, UuidInterface $productId, int $amount = 1): void
    {
        $product = $this->productRepository->getProduct($productId);
        $order = $this->orderRepository->getOrder($orderId);

        $order->addOrderLine($product, $amount);
        $this->orderRepository->save($order);
    }
}