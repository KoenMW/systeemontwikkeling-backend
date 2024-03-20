<?php

namespace Controllers;

use Services\PasswordResetService;
use Exception;

class PasswordResetController extends Controller
{
   private $passwordResetService;

   public function __construct()
   {
      $this->passwordResetService = new PasswordResetService();
   }

   public function reset()
   {
      try {
         $data = json_decode(file_get_contents('php://input'), true);

         if (empty($data) || !isset($data['email'])) {
            throw new Exception('Missing email in request body', 400);
         }

         $email = trim($data['email']);

         $result = $this->passwordResetService->reset($email);

         $this->sendResponse($result['message']);
      } catch (Exception $e) {
         $this->sendResponse($e->getMessage(), $e->getCode());
      }
   }

   public function resetPassword()
   {
      try {
         $data = json_decode(file_get_contents('php://input'), true);

         if (empty($data) || !isset($data['password']) || !isset($data['token'])) {
            throw new Exception('Missing password or token in request body', 400);
         }
         $password = $data['password'];
         $token = $data['token'];

         $this->passwordResetService->resetPassword($token, $password);
         $this->sendResponse('Password reset successful');
      } catch (Exception $e) {
         $this->sendResponse($e->getMessage(), 500);
      }
   }
   private function sendResponse($message, $statusCode = 200)
   {
      http_response_code($statusCode);
      echo json_encode(['message' => $message]);
   }
}
