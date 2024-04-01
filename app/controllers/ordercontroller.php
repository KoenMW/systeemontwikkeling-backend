<?php

namespace Controllers;

use Models\Order;
use Models\checkinDTO;
use Models\Role;
use Services\OrderService;
use Services\InvoiceService;
use Services\UserService;
use Exception;
class OrderController extends Controller
{
    private $orderService;
    private $invoiceService;
    private $userService;
    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->invoiceService = new InvoiceService($this->orderService);
        $this->userService = new UserService();
    }

    /**
     * Retrieves all orders.
     * @author Luko Pecotic
     */
    public function getAllOrders()
    {
        try {
            if (!$this->checkForJwt([2])) return;

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

            //if (!$this->checkForJwt([1, 2])) return;
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

            //if (!$this->checkForJwt([1, 2])) return;

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

            if (!$this->checkForJwt([2])) return;
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
            if (!$this->checkForJwt([2])) return;
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
            if (!$this->checkForJwt([2])) return;
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
    public function generateAndSendInvoice()
    {
        echo realpath(__DIR__ . "/../../storage/qr-codes/");
        try {
            // Assuming you receive order IDs as a JSON payload
            $data = json_decode(file_get_contents('php://input'), true);
            $orderIds = $data['orderIds'] ?? [];

            // Ensure order IDs were provided
            if (empty($orderIds)) {
                throw new Exception("Order IDs are required.");
            }
            $this->orderService->generateEventQrCodes($orderIds);

            // Fetch order details to get user email. This assumes all orders belong to the same user.
            $ordersDetails = $this->orderService->getOrderDetailsByIds($orderIds);
            if (empty($ordersDetails)) {
                throw new Exception("Order details could not be fetched.");
            }

            // Send the invoice via email
            $userEmail = $ordersDetails[0]->email;
            $this->invoiceService->sendInvoiceEmail($orderIds, $userEmail);

            $this->respond(['message' => 'Invoice sent successfully.']);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
}
