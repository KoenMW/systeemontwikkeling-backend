<?php

namespace Controllers;

use Models\Order;
use Models\checkinDTO;
use Models\Role;
use Services\OrderService;
use \Stripe\Stripe;
use \Stripe\PaymentIntent;

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

         if (!$this->checkForJwt([1, 2])) return;
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

         if (!$this->checkForJwt([1, 2])) return;

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
        $jwtData = $this->checkForJwt(0);
        $userId = $jwtData->data->id;

        $data = json_decode(file_get_contents('php://input'), true);
        $tickets = $data['tickets'] ?? [];

        if (empty($tickets)) {
            $this->respondWithError(400, "Tickets array is required.");
            return;
        }

        $createdOrders = [];
        foreach ($tickets as $ticket) {
            $order = new Order();
            $order->event_id = $ticket['event_id'];
            $order->user_id = $userId;
            $order->quantity = $ticket['quantity'];


            $createdOrder = $this->orderService->createOrder($order);
            if ($createdOrder) {
                $createdOrders[] = $createdOrder;
            } else {
                throw new \Exception("Failed to create order for ticket: " . json_encode($ticket));
            }
        }

        // Respond with the created orders
        $this->respond($createdOrders);
    } catch (\Exception $e) {
        error_log($e->getMessage());
        $this->respondWithError(500, "An error occurred while creating the order(s).");
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
   public function createPayment()
   {
      $data = json_decode(file_get_contents('php://input'), true);
      try {
         Stripe::setApiKey('sk_test_51OyXZUA3RX6WGrx7c8ZQtFxzBRGpP1We1kxHBzbsA3Xwyzw5bURSlt4lQMcoxoAa2QApMy5PQa1e9Ke3zbuUqSfY00NbQbaIfu');

         $paymentIntent = PaymentIntent::create([
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'payment_method_types' => ['card'],
            'payment_method_data' => [
               'type' => 'card',
               'card' => ['token' => $data['payment_method_data']['card']['token']]
            ],
            'confirmation_method' => 'manual',
            'confirm' => true,
            'receipt_email' => $data['email'],
         ]);

         http_response_code(200);
         echo json_encode(['clientSecret' => $paymentIntent->client_secret]);
      } catch (\Exception $e) {
         http_response_code(500);
         echo json_encode(['error' => $e->getMessage()]);
      }
   }
}
