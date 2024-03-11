<?php

namespace Controllers;

use Services\PasswordResetService;
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

        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data) || !isset($data['email'])) {
            $this->sendResponse('Missing email in request body');
        }

        $email = trim($data['email']);

        $result = $this->passwordResetService->resetPassword($email);

        $this->sendResponse($result['message']);
    }
}
