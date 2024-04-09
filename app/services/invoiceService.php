<?php

namespace Services;

use Models\Order;
use Models\invoiceDTO;
use Services\OrderService;
use TCPDF;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class InvoiceService
{

   private $orderService;
   private $mailer;

   public function __construct(OrderService $orderService)
   {
      $this->orderService = $orderService;
      $this->mailer = new PHPMailer();
      $this->configureMailer();
   }
   private function configureMailer()
   {
      $env = parse_ini_file('../.env');

      $this->mailer->isSMTP();
      $this->mailer->Host = $env['SMTP_HOST'];
      $this->mailer->Port = $env['SMTP_PORT'];
      $this->mailer->SMTPSecure = $env['SMTP_SECURE'];
      $this->mailer->SMTPAuth = $env['SMTP_AUTH'];
      $this->mailer->Username = $env['SMTP_USERNAME'];
      $this->mailer->Password = $env['SMTP_PASSWORD'];
      $this->mailer->setFrom($env['SMTP_FROM_ADDRESS'], $env['SMTP_FROM_NAME']);
   }

   public function generateInvoiceData(array $orderIds)
   {
      $orderDetails = $this->orderService->getOrderDetailsByIds($orderIds);

      // Initialize totals
      $subtotalPerItem = [];
      $totalAmount = 0;

      // Loop through each order to calculate subtotals and total amount
      foreach ($orderDetails as $order) {
         $subtotal = $order->quantity * $order->price; // Corrected: Accessing properties with object syntax
         $subtotalPerItem[] = [
            'username' => $order->username,
            'phoneNumber' => $order->phoneNumber,
            'address' => $order->address,
            'email' => $order->email,
            'eventName' => $order->EventTitle,
            'quantity' => $order->quantity,
            'pricePerItem' => $order->price,
            'subtotal' => $subtotal,
            'startTime' => $order->startTime,
            'endTime' => $order->endTime,
            'location' => $order->location
         ];
         $totalAmount += $subtotal;
      }

      // Invoice data
      $invoiceData = [
         'invoiceNumber' => uniqid('INV-'), // Example invoice number generation
         'invoiceDate' => date('Y-m-d'),
         'items' => $subtotalPerItem,
         'totalAmount' => $totalAmount
      ];

      return $invoiceData;
   }

   public function createInvoicePdf(array $orderIds)
   {
      $invoiceData = $this->generateInvoiceData($orderIds);

      // Initialize your PDF library and start adding content
      $pdf = new TCPDF();
      $pdf->AddPage();
      $pdf->SetFont('helvetica', '', 12);

      // Adding invoice header
      $pdf->Write(0, "Invoice Number: {$invoiceData['invoiceNumber']}\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Invoice Date: {$invoiceData['invoiceDate']}\n\n", '', 0, 'L', true, 0, false, false, 0);

      // Adding client details (assuming all orders belong to the same user)
      $firstItem = $invoiceData['items'][0];
      $pdf->Write(0, "Username: {$firstItem['username']}\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Phone Number: {$firstItem['phoneNumber']}\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Address: {$firstItem['address']}\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Email: {$firstItem['email']}\n\n", '', 0, 'L', true, 0, false, false, 0);

      // Looping through items to add them to the PDF
      foreach ($invoiceData['items'] as $item) {
         $itemTax = $item['subtotal'] * 0.09; // 9% tax calculation
         $itemTotal = $item['subtotal'] + $itemTax;
         $pdf->Write(0, "{$item['eventName']} - Quantity: {$item['quantity']}, Price per item: €{$item['pricePerItem']}, Subtotal: €{$item['subtotal']}, Tax: €$itemTax, Total: €$itemTotal\n", '', 0, 'L', true, 0, false, false, 0);
      }

      // Calculating total tax and final total
      $totalTax = $invoiceData['totalAmount'] * 0.09;
      $finalTotal = $invoiceData['totalAmount'] + $totalTax;

      // Adding total amounts to the PDF
      $pdf->Write(0, "\nTotal Amount Before Tax: €{$invoiceData['totalAmount']}\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Total Tax (9%): €$totalTax\n", '', 0, 'L', true, 0, false, false, 0);
      $pdf->Write(0, "Final Total: €$finalTotal\n", '', 0, 'L', true, 0, false, false, 0);

      // Specify the file path for saving the PDF
      $filePath = __DIR__ . "/../storage/invoices/invoice_{$invoiceData['invoiceNumber']}.pdf";

      // Save the PDF file
      $pdf->Output($filePath, 'F');

      return $filePath;
   }
   public function sendInvoiceEmail(array $orderIds, $userEmail)
   {
      // Generate and attach invoice PDF
      $invoiceFilePath = $this->createInvoicePdf($orderIds);
      $qrdata = $this->generateInvoiceData($orderIds);
      $this->mailer->addAddress($userEmail);
      $this->mailer->Subject = 'Invoice for your order';
      $this->mailer->Body = "Hey " . $userEmail . ",\n\nHere is your invoice and Tickets.\n\nKind regards,\nThe Festival";
      $this->mailer->addAttachment($invoiceFilePath);
      $this->mailer->isHTML(true);

      // Create a new PDF for QR codes
      $qrPdf = new TCPDF();
      foreach ($qrdata['items'] as $item) {
         $qrPdf->AddPage();
         $qrPdf->writeHTMLCell(0, 0, '', '', "Event: {$item['eventName']}<br>Ticket Amount: {$item['quantity']}<br>Time: {$item['startTime']} - {$item['endTime']}<br>Location: {$item['location']}", 0, 1, 0, true, 'L', true);
         foreach ($orderIds as $orderId) {
            // Define the path for the QR code image
            $qrCodePath = __DIR__ . "/../storage/qr-codes/event_$orderId.png";

            // Check if the QR code file exists before trying to attach it
            if (file_exists($qrCodePath)) {
               // Add a page for each QR code and insert the QR code as an image
               $qrPdf->Image($qrCodePath, 30, 50, 80, 80, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
            }
         }
      }

      // Save the QR codes PDF file
      $qrPdfFilePath = __DIR__ . "/../storage/qr-codes/qrcodes_" . uniqid() . ".pdf";
      $qrPdf->Output($qrPdfFilePath, 'F');

      // Attach the QR codes PDF to the email
      if (file_exists($qrPdfFilePath)) {
         $this->mailer->addAttachment($qrPdfFilePath, "QR Codes.pdf");
      }

      // Send the email
      try {
         if (!$this->mailer->send()) {
            throw new Exception('Error sending email: ' . $this->mailer->ErrorInfo);
         } else {
            // Cleanup: Delete the QR codes PDF file after sending the email
            unlink($qrPdfFilePath);
            return ['message' => 'Invoice email sent successfully.'];
         }
      } catch (Exception $e) {
         // Cleanup: Attempt to delete the QR codes PDF file in case of error
         if (file_exists($qrPdfFilePath)) {
            unlink($qrPdfFilePath);
         }
         return ['error' => 'Error sending email: ' . $e->getMessage()];
      }
   }

   public function sendRecoveryEmail($userEmail)
   {
      $this->mailer->addAddress($userEmail);
      $this->mailer->Subject = 'Payment Recovery Link';

      // Generate the recovery link
      $link = "http://localhost:5173/shop";
      $expiration = time() + (24 * 60 * 60);
      $expirationDate = date('Y-m-d H:i:s', $expiration);


      $this->mailer->Body = "Hey " . $userEmail . ",\n\n"
         . "We noticed that your payment attempt was not successful.\n\n"
         . "Please use the following link to retry your payment: \n"
         . "<a href=\"$link\">Click Here</a>\n\n"
         . "This link will expire on $expirationDate.\n\n"
         . "Kind regards,\n"
         . "The Festival";

      $this->mailer->isHTML(true);

      // Send the email
      try {
         $this->mailer->send();
         return ['message' => 'Recovery email sent successfully.'];
      } catch (Exception $e) {
         return ['error' => 'Failed to send recovery email: ' . $e->getMessage()];
      }
   }
}
