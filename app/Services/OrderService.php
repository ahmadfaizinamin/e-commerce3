<?php
namespace App\Services;

use App\Jobs\SendReceiptMail;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function checkoutProcess($user, array $items)
    {
        $totalPrice = 0;
        $cartItems = [];

        foreach ($items as $item) {
            $product = $this->productRepository->getById($item['product_id']);

            if ($product['stock'] < $item['quantity']) {
                throw new Exception("Stok produck ID: " . $product['id'] . " tersedia hanya " . $product['stock']);
            }

            $totalPrice += $product['price'] * $item['quantity'];

            $cartItems[] = [
                'product' => $product,
                'quantity' => $item['quantity']
            ];
        }

        return DB::transaction(function() use ($user, $cartItems, $totalPrice) {
            $order = $this->orderRepository->createOrder([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            foreach ($cartItems as $data) {
                $this->orderRepository->createOrderItem([
                    'order_id' => $order['id'],
                    'product_id' => $data['product']['id'],
                    'price' => $data['product']['price'],
                    'quantity' => $data['quantity']
                ]);

                $this->productRepository->update($data['product']['id'], [
                    'stock' => $data['product']['stock'] - $data['quantity']
                ]);
            }

            SendReceiptMail::dispatch([
                'order_id' => $order->id,
                'email' => $user->email,
                'total_price' => $totalPrice
            ]);

            return $order;
        });
    }

    public function cancelPendingOrders($timeLimit)
    {
        $orderExpired = $this->orderRepository->getExpiredOrder($timeLimit);
        $totalCancel = 0;

        if ($orderExpired->isEmpty()) {
            return $totalCancel;
        }

        foreach ($orderExpired as $order) {
            $this->orderRepository->updateStatusOrder($order['id'], 'cancelled');

            $totalCancel++;
        }

        return $totalCancel;
    }
}