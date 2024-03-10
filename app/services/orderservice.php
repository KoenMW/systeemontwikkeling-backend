<?php
namespace Services;

use Models\Order;
use Repositories\OrderRepository;

class OrderService {
    private $orderRepository;

    public function __construct() {
        $this->orderRepository = new OrderRepository();
    }

    public function getAllOrders(int $offset = null, int $limit = null) {
        return $this->orderRepository->getAllOrders($offset, $limit);
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