<?php
namespace Repositories;

use Models\Order;

class OrderRepository extends Repository {

    public function getAllOrders() {
        try {
            $sql = "SELECT * FROM Orders";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $ordersData = $stmt->fetchAll();
            
            if (!$ordersData) {
                throw new \Exception("Failed to fetch orders from the database.");
            }
            
            $orders = [];
            foreach ($ordersData as $orderData) {
                $order = new Order();
                $order->id = $orderData['id'];
                $order->event_id = $orderData['event_id'];
                $order->user_id = $orderData['user_id'];
                $order->quantity = $orderData['quantity'];
                $order->comment = $orderData['comment'];
                $order->paymentDate = $orderData['paymentDate'];
                $orders[] = $order;
            }
            return $orders;
        } catch (\Exception $e) {
            error_log('Error fetching orders: ' . $e->getMessage());
            
            $this->respondWithError(500, "Internal Server Error");
            
            return [];
        }
    }

    public function createOrder(Order $order) {
        try {
            $sql = "INSERT INTO Orders (event_id, user_id, quantity, comment, paymentDate) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Failed to prepare statement");
            }
            $paymentDate = new \DateTime($order->paymentDate);
            $result = $stmt->execute([$order->event_id, $order->user_id, $order->quantity, $order->comment, $paymentDate->format('Y-m-d')]);
            if (!$result) {
                throw new \Exception("Failed to execute statement");
            }
            return $this->connection->lastInsertId();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateOrder(Order $order) {
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

    public function deleteOrder(int $id) {
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
}