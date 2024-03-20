<?php

namespace Controllers;

use Models\Order;
use Models\checkinDTO;
use Models\Role;
use Services\OrderService;

class OrderController extends Controller
{
    private $orderService;

    public function __construct()
    {
        $this->orderService = new OrderService();
    }

    /**
     * Retrieves all orders.
     * @author Luko Pecotic
     */
    public function getAllOrders()
    {
        try {
            if (!$this->checkForJwt(2)) return;

            $orders = $this->orderService->getAllOrders();

            if (!$orders) {
                $this->respondWithError(404, "Orders not found");
                return;
            }

            $this->respond($orders);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving orders");
        }
    }

    /**
     * Get order by id
     * @param int $id
     * @return void
     * @author Koen Wijchers
     */
    public function getById($id)
    {
        try {
            $order = $this->orderService->getById($id);

            if (!$order) {
                $this->respondWithError(404, "Order not found");
                return;
            }

            $this->respond($order);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the order");
        }
    }

    /**
     * @author Koen Wijchers
     */
    public function checkOrderById($id)
    {
        try {

            if (!$this->checkForJwt(1)) return;
            $order = $this->orderService->checkOrderById($id);
            if (!$order) {
                $this->respondWithError(404, "Order not found");
                return;
            }

            $this->respond($order);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while retrieving the order");
        }
    }

    /**
     * sets the checkin status of an order
     * @author Koen Wijchers
     */
    public function setCheckin()
    {
        try {

            if (!$this->checkForJwt(1)) return;

            $DTO = $this->createObjectFromPostedJson(checkinDTO::class);
            if (!isset($DTO->id, $DTO->checkedIn)) {
                $this->respondWithError(400, "Missing order data");
                return;
            }
            $succes = $this->orderService->setCheckin($DTO);
            $succes ? $this->respond($DTO->checkedIn) : $this->respondWithError(500, "Failed to check in the order");
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while checking in the order");
        }
    }

    /**
     * Creates a new order.
     * @author Luko Pecotic
     */
    public function createOrder()
    {
        try {
            $order = $this->createObjectFromPostedJson(Order::class);

            if (!isset($order->event_id, $order->user_id, $order->quantity)) {
                $this->respondWithError(400, "Missing order data");
                return;
            }
            $createdOrder = $this->orderService->createOrder($order);
            if ($createdOrder) {
                $this->respond($createdOrder);
            } else {
                $this->respondWithError(500, "Failed to create order");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while creating the order");
        }
    }

    /**
     * Updates an existing order.
     * @author Luko Pecotic
     */
    public function updateOrder()
    {
        try {
            $order = $this->createObjectFromPostedJson(Order::class);
            if (!isset($order->id, $order->event_id, $order->user_id, $order->quantity, $order->comment, $order->paymentDate)) {
                $this->respondWithError(400, "Missing order data");
                return;
            }
            $updated = $this->orderService->updateOrder($order);
            if ($updated) {
                $this->respond($order);
            } else {
                $this->respondWithError(500, "Failed to update order");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while updating the order");
        }
    }

    /**
     * Deletes an existing order.
     * @author Luko Pecotic
     */
    public function deleteOrder()
    {
        try {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $deleted = $this->orderService->deleteOrder($id);
                if ($deleted) {
                    $this->respond(['message' => 'Order deleted successfully']);
                } else {
                    $this->respondWithError(404, "Order not found");
                }
            } else {
                $this->respondWithError(400, "Invalid id");
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $this->respondWithError(500, "An error occurred while deleting the order");
        }
    }
}
