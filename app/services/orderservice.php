<?php

namespace Services;

use Models\checkinDTO;
use Models\Order;
use Models\checkOrderDTO;
use Repositories\OrderRepository;
use Repositories\EventRepository;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Models\invoiceDTO;

class OrderService
{
    private $orderRepository;
    private $eventRepository;
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->eventRepository = new EventRepository();
    }

    /**
     * Retrieves all orders from the database.
     * @return array An array of Order objects representing all orders in the database
     * @throws Exception If there's an error fetching the orders from the database
     * @author Luko Pecotic
     */
    public function getAllOrders()
    {
        return $this->orderRepository->getAllOrders();
    }

    
    public function createOrder(Order $order)
{
    try {
        $order->comment ?? $order->comment = '';
        $createdOrder = $this->orderRepository->createOrder($order);

        $event = $this->eventRepository->getEventById($order->event_id);
        if ($event) {
            $updatedTicketAmount = $event->ticket_amount - $order->quantity;
            $this->eventRepository->updateEventTicketAmount($order->event_id, $updatedTicketAmount);
        }

        return $createdOrder;
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
        return $this->orderRepository->updateOrder($order);
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
        return $this->orderRepository->deleteOrder($id);
    }

    /**
     * Get order by id
     * @param int $id
     * @return Order
     * @throws \Exception
     * @author Koen Wijchers
     */
    public function getById($id)
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
    public function checkOrderById($id)
    {
        return $this->orderRepository->checkOrder($id);
    }
    public function getOrderDetailsByIds(array $orderIds)
    {
        return $this->orderRepository->getOrderDetailsByIds($orderIds);
    }
    public function generateEventQrCodes(array $eventIds)
    {
        $writer = new PngWriter();

        foreach ($eventIds as $eventId) {
            // Create a new QR Code instance
            $qrCode = QrCode::create($eventId)
                ->setSize(300) // Size of the QR Code
                ->setMargin(10); // Margin around the QR Code

            // Define the output path for the QR code image
            $outputPath = __DIR__ . "/../storage/qr-codes/event_$eventId.png";
            // Write the QR code to a file
            $writer->write($qrCode)->saveToFile($outputPath);

            echo "QR Code generated for event ID $eventId at $outputPath\n";
        }
    }
}
