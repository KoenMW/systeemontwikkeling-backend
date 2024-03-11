<?php

namespace Services;

use Models\checkinDTO;
use Models\Order;
use Models\checkOrderDTO;
use Repositories\OrderRepository;

class OrderService
{
    private $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function getAllOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    public function getById(int $id)
    {
        return $this->orderRepository->getById($id);
    }

    public function setCheckin(checkinDTO $checkinDTO)
    {
        return $this->orderRepository->setCheckin($checkinDTO);
    }

    public function checkOrderById(int $id)
    {
        return $this->orderRepository->checkOrder($id);
    }

    public function createOrder(Order $order)
    {
        return $this->orderRepository->createOrder($order);
    }

    public function updateOrder(Order $order)
    {
        return $this->orderRepository->updateOrder($order);
    }

    public function deleteOrder(int $id)
    {
        return $this->orderRepository->deleteOrder($id);
    }
}
