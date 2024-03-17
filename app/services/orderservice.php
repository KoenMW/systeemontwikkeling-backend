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

    public function createOrder(Order $order)
    {
        $order->comment ?? $order->comment = '';
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

    /**
     * Get order by id
     * @param int $id
     * @return Order
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getById(int $id)
    {
        return $this->orderRepository->getById($id);
    }

    /**
     * sets the checkedIn value of an order
     * @param checkinDTO $checkinDTO
     * @return bool
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function setCheckin(checkinDTO $checkinDTO)
    {
        return $this->orderRepository->setCheckin($checkinDTO);
    }

    /**
     * gets the order and the event data
     * @param int $id
     * @return checkOrderDTO
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function checkOrderById(int $id)
    {
        return $this->orderRepository->checkOrder($id);
    }
}
