<?php
namespace App\Repositories\Interfaces;

interface OrderRepositoryInterface
{
    public function createOrder(array $data);
    public function createOrderItem(array $data);
    public function getItemByOrderId($orderId);
    public function getExpiredOrder($timeLimit);
    public function updateStatusOrder($id, string $status);
}