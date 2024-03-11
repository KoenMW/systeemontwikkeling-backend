<?php

namespace Services;

use Repositories\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use Exception;

class PasswordResetService
{
    private $userRepository;
    private $mailer;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->mailer = new PHPMailer();
        $this->configureMailer();
    }

    private function configureMailer()
    {
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.example.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'nick.schaap127@gmail.com';
        $this->mailer->Password = 'ijlf nadp miej vftf';
        $this->mailer->setFrom('nick.schaap127@gmail.com', 'Nick Schaap');
    }

    public function resetPassword($email)
    {
        $user = $this->userRepository->getUserByEmail($email);

        if (!$user) {
            return ['message' => 'No account found with that email address.'];
        }

        $resetToken = bin2hex(random_bytes(16));
        $resetExpiry = date('Y-m-d H:i:s', time() + 3600);

        $this->userRepository->updateResetToken($user['id'], $resetToken, $resetExpiry);

        $resetLink = "http://localhost:5173/reset-password?token=$resetToken";

        $this->mailer->addAddress($email);
        $this->mailer->Subject = 'Password Reset Request';
        $this->mailer->Body = "Click on the following link to reset your password: $resetLink";

        try {
            if (!$this->mailer->send()) {
                throw new Exception('Error sending email: ' . $this->mailer->ErrorInfo);
            } else {
                return ['message' => 'Password reset email sent successfully.'];
            }
        } catch (Exception $e) {
            return ['message' => $e->getMessage()];
        }
    }
}
