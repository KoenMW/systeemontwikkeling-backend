<?php
namespace Services;

use Models\Order;
use Repositories\OrderRepository;

class OrderService {
    private $orderRepository;

    public function __construct() {
        $this->orderRepository = new OrderRepository();
    }

    public function getAllOrders() {
        return $this->orderRepository->getAllOrders();
    }

    public function createOrder(Order $order) {
        return $this->orderRepository->createOrder($order);
    }

    public function updateOrder(Order $order) {
        return $this->orderRepository->updateOrder($order);
    }

    public function deleteOrder(int $id) {
        return $this->orderRepository->deleteOrder($id);
    }
}