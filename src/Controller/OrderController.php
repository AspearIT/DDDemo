<?php
declare(strict_types=1);

namespace AspearIT\DDDemo\Controller;

use AspearIT\DDDemo\Domain\Order\Exception\LineAmountException;
use AspearIT\DDDemo\Domain\Order\Exception\OrderNotFoundException;
use AspearIT\DDDemo\Domain\Order\Exception\ProductNotFoundException;
use AspearIT\DDDemo\Domain\Order\Service\OrderService;

readonly class OrderController
{
    public function __construct(private OrderService $orderService) {}

    public function addOrderLine(OrderRequest $request)
    {
        try {
            $this->orderService->addOrderLine(
                Uuid::fromString($request->post('order_id')),
                Uuid::fromString($request->post('product_id')),
                (int) $request->post('amount'),
            );
        } catch (LineAmountException) {
            return redirect()->back()->withMessage('Amount should be greater than 0');
        } catch (ProductNotFoundException) {
            return redirect()->back()->withMessage('Product not found');
        } catch (OrderNotFoundException) {
            return redirect()->back()->withMessage('Order not found');
        }

        return redirect()->back()->withMessage('Orderline added');
    }
}