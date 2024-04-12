<?php

namespace Repositories;

use Models\Order;
use Models\OrderDTO;
use Models\checkinDTO;
use PDO;
use Models\invoiceDTO;

class OrderRepository extends Repository
{
   /**
    * Retrieves all orders from the database.
    * @return array An array of Order objects representing all orders in the database
    * @throws Exception If there's an error fetching the orders from the database
    * @author Luko Pecotic
    */
   public function getAllOrders()
   {
      try {
         $sql = "
            SELECT 
                Orders.*, 
                users.username, 
                events.title AS eventName
            FROM 
                Orders
            INNER JOIN 
                users ON Orders.user_id = users.id
            INNER JOIN 
                events ON Orders.event_id = events.id
        ";
         $stmt = $this->connection->prepare($sql);
         $stmt->execute();
         $orders = $stmt->fetchAll(PDO::FETCH_CLASS, OrderDTO::class);
         return $orders;
      } catch (\Exception $e) {
         error_log('Error fetching orders: ' . $e->getMessage());
         return [];
      }
   } 
   /**
    * Retrieves all orders from the database.
    * @return array An array of Order objects representing all orders in the database
    * @throws Exception If there's an error fetching the orders from the database
    * @author nick
    */
   public function createOrder(Order $order)
   {
      try { 
         $id = uniqid("", true);
         $paymentDate = date('Y-m-d');

         $sql = "INSERT INTO Orders (id, event_id, user_id, quantity, comment, paymentDate) VALUES (?, ?, ?, ?, ?, ?)";
         $stmt = $this->connection->prepare($sql);

         if (!$stmt) {
            throw new \Exception("Failed to prepare statement");
         }

         $stmt->execute([$id, $order->event_id, $order->user_id, $order->quantity, $order->comment, $paymentDate]);

         return $id;
      } catch (\Exception $e) {
         error_log($e->getMessage());
         return false;
      }
   }

   /**
    * Updates an existing order in the database.
    * @param Order $order The order to be updated
    * @return bool True if the order was updated successfully, false otherwise
    * @throws Exception If there's an error preparing or executing the SQL statement
    * @author Luko Pecotic
    */
   public function updateOrder(Order $order)
   {
      try {
         $sql = "UPDATE Orders SET event_id = ?, user_id = ?, quantity = ?, comment = ?, paymentDate = ? WHERE id = ?";
         $stmt = $this->connection->prepare($sql);
         if (!$stmt) {
            throw new \Exception("Failed to prepare statement");
         }
         $paymentDate = new \DateTime($order->paymentDate);
         $result = $stmt->execute([$order->event_id, $order->user_id, $order->quantity, $order->comment, $paymentDate->format('Y-m-d'), $order->id]);
         if (!$result) {
            throw new \Exception("Failed to execute statement");
         }
         return $stmt->rowCount() > 0;
      } catch (\Exception $e) {
         error_log($e->getMessage());
         return false;
      }
   }

   /**
    * Deletes an existing order from the database.
    * @param int $id The id of the order to be deleted
    * @return bool True if the order was deleted successfully, false otherwise
    * @throws Exception If there's an error preparing or executing the SQL statement
    * @author Luko Pecotic
    */

   public function deleteOrder($id)
   {
      try {
         $sql = "DELETE FROM Orders WHERE id = ?";
         $stmt = $this->connection->prepare($sql);
         if (!$stmt) {
            throw new \Exception("Failed to prepare statement");
         }
         $result = $stmt->execute([$id]);
         if (!$result) {
            throw new \Exception("Failed to execute statement");
         }
         return $stmt->rowCount() > 0;
      } catch (\Exception $e) {
         error_log($e->getMessage());
         return false;
      }
   }

   /**
    * gets the order by id
    * @param int $id
    * @return Order|null
    * @throws \Exception
    * @author Koen Wijchers
    */
   public function getById(int $id)
   {
      try {
         $stmt = $this->connection->prepare("SELECT * FROM Orders WHERE id = :id");
         $stmt->bindParam(':id', $id);
         $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, Order::class);
         $orders = $stmt->fetch();
         return $orders;
      } catch (\Exception $e) {
         error_log('Error fetching order: ' . $e->getMessage());
         return null;
      }
   }

   /**
    * gets the order and the event data
    * @param int $id
    * @return checkOrderDTO
    * @author Koen Wijchers
    */
   public function checkOrder($id)
   {
      try {
         $stmt = $this->connection->prepare("
                SELECT Orders.id, 
                Orders.event_id, 
                Orders.user_id, 
                Orders.quantity, 
                Orders.comment, 
                Orders.paymentDate, 
                Orders.checkedIn,
                events.title, 
                events.startTime, 
                events.endTime, 
                events.location
                FROM Orders 
                LEFT JOIN events ON Orders.event_id = events.id 
                WHERE Orders.id = :id
            ");
         $stmt->bindParam(':id', $id);
         $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\checkOrderDTO');
         $orders = $stmt->fetch();
         return $orders;
      } catch (\Exception $e) {
         error_log('Error fetching order: ' . $e->getMessage());
         return null;
      }
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
      try {
         $checkin = $checkinDTO->checkedIn ? 1 : 0;
         $stmt = $this->connection->prepare("UPDATE Orders SET checkedIn = :checkedIn WHERE id = :id");
         $stmt->bindParam(':id', $checkinDTO->id);
         $stmt->bindParam(':checkedIn', $checkin, PDO::PARAM_BOOL);
         $stmt->execute();
         return $stmt->rowCount() > 0;
      } catch (\Exception $e) {
         error_log('Error checking in order: ' . $e->getMessage());
         return false;
      }
   }
   /**
    * gets the order and the event data
    * @param int $id
    * @return InvoiceDTO
    * @throws \Exception
    * @author Omar Al Sayasna
    */
   function getOrderDetailsByIds($orderIds)
   {
      try {
         $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
         $stmt = $this->connection->prepare("
            SELECT 
            Orders.id AS OrderID,
            Orders.quantity,
            Orders.paymentDate,
            users.username,
            users.email,
            users.phoneNumber,
            users.address,
            events.title AS EventTitle,
            events.startTime,
            events.endTime,
            events.price,
            events.location,
            events.eventType
        FROM 
            Orders
        JOIN 
            users ON Orders.user_id = users.id
        JOIN 
            events ON Orders.event_id = events.id
        WHERE 
            Orders.id IN ($placeholders)
            ");
         $stmt->execute($orderIds);
         $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\InvoiceDTO');
         $orders = $stmt->fetchAll();
         return $orders;
      } catch (\Exception $e) {
         error_log('Error fetching order: ' . $e->getMessage());
         return null;
      }
   }
}
