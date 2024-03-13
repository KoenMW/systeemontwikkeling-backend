<?php

namespace Services;

use Repositories\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
      $this->mailer->Host = 'smtp.gmail.com';
      $this->mailer->Port = 587;
      $this->mailer->SMTPSecure = 'tls';
      $this->mailer->SMTPAuth = true;
      $this->mailer->Username = 'nick.schaap127@gmail.com'; // Update with your email credentials
      $this->mailer->Password = 'ijlf nadp miej vftf'; // Update with your email password
      $this->mailer->setFrom('nick.schaap127@gmail.com', 'Nick Schaap'); // Update with your name and email
   }

   public function reset($email)
{
    $user = $this->userRepository->getUserByEmail($email);

    if (!$user) {
        return ['error' => 'No account found with that email address.'];
    }

    $resetToken = bin2hex(random_bytes(16));
    $currentTime = time(); // Get the current time
    $resetExpiry = date('Y-m-d H:i:s', $currentTime + 3600); // Set expiration time 1 hour from now

    try {
        $this->userRepository->updateResetToken($user->id, $resetToken, $resetExpiry);
    } catch (\Exception $e) {
        return ['error' => 'Error updating reset token: ' . $e->getMessage()];
    }

    $resetLink = "http://localhost:5173/reset-password?token=$resetToken";

    $this->mailer->addAddress($email);
    $this->mailer->Subject = 'Password Reset Request';
    $this->mailer->Body = "Hey " . $email . ",\n\n"
        . "Here is the password reset link. Please click below:\n\n"
        . "<a href=\"$resetLink\">Click Here</a>\n\n"
        . "Kind regards,\n"
        . "Your Name";

    $this->mailer->isHTML(true);

    try {
        if (!$this->mailer->send()) {
            throw new Exception('Error sending email: ' . $this->mailer->ErrorInfo);
        } else {
            return ['message' => 'Password reset email sent successfully.'];
        }
    } catch (Exception $e) {
        return ['error' => 'Error sending email: ' . $e->getMessage()];
    }
}

   public function resetPassword($token, $password)
   {
      // Validate the token and update the password
      try {
         $this->validateToken($token);
         $this->userRepository->updatePassword($token, $password);
      } catch (Exception $e) {
         throw new Exception('Failed to reset password: ' . $e->getMessage());
      }
   }

   public function validateToken($token)
   {
      // Check if the token exists in the database and is not expired
      $tokenData = $this->userRepository->getUserByResetToken($token);

      if (!$tokenData || strtotime($tokenData->reset_token_expiry) < time()) {
         throw new Exception('Invalid or expired token');
      }
   }
}
