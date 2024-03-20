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
        header("Access-Control-Allow-Origin: http://localhost:5173");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type");

        try {
            // Read JSON input
            $data = json_decode(file_get_contents('php://input'), true);

            // Validate email presence
            if (empty($data) || !isset($data['email'])) {
                throw new Exception('Missing email in request body', 400);
            }

            $email = trim($data['email']);

            // Attempt password reset
            $result = $this->passwordResetService->reset($email);

            // Send response
            $this->sendResponse($result['message']);
        } catch (Exception $e) {
            // Handle exception
            $this->sendResponse($e->getMessage(), $e->getCode());
        }
    }

    public function resetPassword($request)
    {
        // Extract token and new password from the request
        $token = $request->query->get('token');
        $password = $request->input('password');

        try {
            // Reset the password using the token
            $this->passwordResetService->resetPassword($token, $password);

            // Password reset successful, send a success response
            $this->sendResponse('Password reset successful');
        } catch (Exception $e) {
            // Handle errors
            $this->sendResponse($e->getMessage(), 500); // Sending 500 status code for internal server error
        }
    }
    private function sendResponse($message, $statusCode = 200)
    {
        http_response_code($statusCode);
        echo json_encode(['message' => $message]);
    }
}
