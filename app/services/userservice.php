<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class UserService
{

   private $repository;
   private $mailer;
   function __construct()
   {
      $this->repository = new UserRepository();
      $this->mailer = new PHPMailer();
      $this->configureMailer();
   }

   public function checkUsernamePassword($email, $password)
   {
      return $this->repository->login($email, $password);
   }

   public function createUser(User $user)
   {
      $user->img ?? $user->img = '';
      $this->repository->signUp($user);
   }

   public function checkEmailPassword($email, $password)
   {
      return $this->repository->checkEmailPassword($email, $password);
   }

   public function updateResetToken($userId, $token, $expiry)
   {
      return $this->repository->updateResetToken($userId, $token, $expiry);
   }
   public function getUsers($searchEmail = null, $filterRole = null, $sortByCreateDate = 'ASC')
   {
      return $this->repository->getUsers($searchEmail, $filterRole, $sortByCreateDate);
   }

   /**
    * Updates a user with the given id
    * @param int $id
    * @param string $username
    * @param string $email
    * @param int $phoneNumber
    * @param string $address
    * @author Luko Pecotic
    */
   public function updateUser($user)
   {
      $this->repository->updateUser($user);
   }

   /**
    * Changes the password of the user with the given id
    * @param int $id
    * @param string $currentPassword
    * @param string $newPassword
    * @throws Exception If the current password is incorrect or if there's an error updating the password in the database
    * @author Luko Pecotic
    */
   public function changePassword($id, $currentPassword, $newPassword)
   {
      $this->repository->changePassword($id, $currentPassword, $newPassword);
   }

   /**
    * Uploads a profile picture for the user with the given id
    * @param int $id
    * @param string $base64Image
    * @throws Exception If there's an error updating the profile picture in the database
    * @author Luko Pecotic
    */
   public function uploadProfilePicture($id, $base64Image)
   {
      $this->repository->uploadProfilePicture($id, $base64Image);
   }

   public function deleteUser($id)
   {
      return $this->repository->deleteUser($id);
   }

   /**
    * Fetches a user by their id by calling the corresponding method in the `UserRepository`.
    * @param int $id The id of the user to fetch.
    * @return User The fetched user.
    * @author Luko Pecotic
    */
   public function getUserById($id)
   {
      return $this->repository->getUserById($id);
   }
   /**
    * Configures the mailer with the SMTP settings from the .env file
    * @throws Exception If there's an error reading the .env file or if the mailer settings are invalid
    * @author nick
    */
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
   /**
    * Sends a password reset email to the user with the given email address
    * @param string $email The email address of the user
    * @return array An array containing a success message or an error message if the email could not be sent
    * @throws Exception If there's an error sending the email
    * @author nick
    */
   public function reset($email)
   {
      $user = $this->repository->getUserByEmail($email);

      if (!$user) {
         return ['error' => 'No account found with that email address.'];
      }

      $resetToken = bin2hex(random_bytes(16));
      $currentTime = time();
      $resetExpiry = date('Y-m-d H:i:s', $currentTime + 7200);

      try {
         $this->repository->updateResetToken($user->id, $resetToken, $resetExpiry);
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
         . "The Festival";

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
   /**
    * Resets the password of a user with the given reset token
    * @param string $token The reset token
    * @param string $password The new password
    * @throws Exception If the token is invalid or expired, or if there's an error updating the password in the database
    * @author nick
    */
   public function resetPassword($token, $password)
   {

      try {
         $this->validateToken($token);
         $this->repository->updatePassword($token, $password);
      } catch (Exception $e) {
         throw new Exception('Failed to reset password: ' . $e->getMessage());
      }
   }
   /**
    * Validates a reset token
    * @param string $token The reset token
    * @throws Exception If the token is invalid or expired
    * @author nick
    */
   public function validateToken($token)
   {

      $tokenData = $this->repository->getUserByResetToken($token);

      if (!$tokenData || strtotime($tokenData->reset_token_expiry) < time()) {
         throw new Exception('Invalid or expired token');
      }
   }
}
