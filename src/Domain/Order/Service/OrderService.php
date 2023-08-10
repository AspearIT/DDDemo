<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Domain\Order\Service;

use AspearIT\DDDemo\Domain\Order\Repository\OrderRepositoryInterface;

readonly class OrderService
{
    public function __construct (
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function addOrderLine(Uuid $orderId, Uuid $productId, int $amount = 1): Uuid
    {
        $product = $this->productRepository->getProduct($productId);
        $order = $this->orderRepository->getOrder($orderId);

        $orderLineId = $order->addOrderLine($product, $amount);
        $this->orderRepository->save($order);

        return $orderLineId;
    }
}