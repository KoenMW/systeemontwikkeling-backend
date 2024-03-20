<?php

namespace controllers;

use services\passwordresetservice;
use exception;

class passwordresetcontroller extends controller
{
   private $passwordresetservice;

   public function __construct()
   {
      $this->passwordresetservice = new passwordresetservice();
   }

   public function reset()
{
    try {
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data) || !isset($data['email'])) {
            throw new Exception('missing email in request body', 400);
        }

        $email = trim($data['email']);

        $result = $this->passwordresetservice->reset($email);

        if (isset($result['message'])) {
            $this->sendresponse($result['message']);
        } else {
            $this->sendresponse('An error occurred', 500);
        }
    } catch (Exception $e) {
        $this->sendresponse($e->getMessage(), $e->getCode());
    }
}

   public function resetpassword()
   {
      try {
         $data = json_decode(file_get_contents('php://input'), true);

         if (empty($data) || !isset($data['password']) || !isset($data['token'])) {
            throw new exception('missing password or token in request body', 400);
         }
         $password = $data['password'];
         $token = $data['token'];

         $this->passwordresetservice->resetpassword($token, $password);
         $this->sendresponse('password reset successful');
      } catch (exception $e) {
         $this->sendresponse($e->getmessage(), 500);
      }
   }
   private function sendresponse($message, $statuscode = 200)
   {
      http_response_code($statuscode);
      echo json_encode(['message' => $message]);
   }
}
