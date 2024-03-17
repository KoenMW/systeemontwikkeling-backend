<?php

namespace Repositories;

use Models\Order;
use Models\checkinDTO;
use PDO;

class OrderRepository extends Repository
{

    public function getAllOrders()
    {
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
            return [];
        }
    }

    public function createOrder(Order $order)
    {
        try {
            $id = uniqid("", true);
            $sql = "INSERT INTO Orders (id, event_id, user_id, quantity, comment) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sql);
            if (!$stmt) {
                throw new \Exception("Failed to prepare statement");
            }
            return $stmt->execute([$id, $order->event_id, $order->user_id, $order->quantity, $order->comment]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

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

    public function deleteOrder(int $id)
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
}
