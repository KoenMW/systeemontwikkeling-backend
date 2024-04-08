<?php

namespace Controllers;

use Models\Order;
use Models\checkinDTO;
use Models\OrderDTO;
use Models\Role;
use Services\OrderService;
use Services\InvoiceService;
use Services\UserService;
use Exception;

use \Stripe\Stripe;
use \Stripe\PaymentIntent;

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
         if (!$this->checkForJwt(1)) return;
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
    * @author nick
    */
   public function createOrder()
   {
      try {
         if (!$this->checkForJwt(0)) return;

         $data = json_decode(file_get_contents('php://input'), true);
         $tickets = $data['tickets'] ?? [];
         $userId = $data['userId'] ?? null;

         if (empty($tickets)) {
            $this->respondWithError(400, "Tickets array is required.");
            return;
         }

         $createdOrderIds = [];
         foreach ($tickets as $ticket) {
            $order = new Order();
            $order->event_id = $ticket['id'];
            $order->user_id = $userId;
            $order->quantity = $ticket['quantity'];
            $order->comment = $ticket['comment'];

            $orderId = $this->orderService->createOrder($order);
            if ($orderId) {
               $createdOrderIds[] = $orderId;
            } else {
               throw new \Exception("Failed to create order for ticket: " . json_encode($ticket));
            }
         }
         
         $this->generateAndSendInvoice($createdOrderIds);

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
         if (!$this->checkForJwt(2)) return;
         $order = $this->createObjectFromPostedJson(OrderDTO::class);
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
   public function deleteOrder($id)
   {
      try {
         if (!$this->checkForJwt(2)) return;
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

   public function generateAndSendInvoice($orderIds)
   {
      echo realpath(__DIR__ . "/../../storage/qr-codes/");
      try {
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

   public function createPayment()
   {
      $data = json_decode(file_get_contents('php://input'), true);
      try {
         Stripe::setApiKey(parse_ini_file('../.env')["STRIPE_SECRET_KEY"]);
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
