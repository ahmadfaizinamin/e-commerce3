<?php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use Override;

class OrderRepository implements OrderRepositoryInterface
{
    #[Override]
    public function createOrder(array $data)
    {
        return Order::create($data);
    }

    #[Override]
    public function createOrderItem(array $data)
    {
        return OrderItem::create($data);
    }

    #[Override]
    public function getItemByOrderId($orderId)
    {
        return OrderItem::where('order_id', $orderId)->get();
    }

    #[Override]
    public function getExpiredOrder($timeLimit)
    {
        return Order::where('status', 'pending')
                    ->where('created_at', '<', $timeLimit)
                    ->get();
    }

    #[Override]
    public function updateStatusOrder($id, string $status)
    {
        return Order::where('id', $id)->update(['status' => $status]);
    }
}