<?php

namespace Repositories;

use DateTime;
use PDO;
use PDOException;
use Repositories\Repository;
use Exception;
use Models\User;

class UserRepository extends Repository
{
    function login($email, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare("SELECT id, email, password, role FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

         $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
         $user = $stmt->fetch();

         // verify if the password matches the hash in the database
         $result = $this->verifyPassword($password, $user->password);

         if (!$result)
            return false;

         // do not pass the password hash to the caller
         $user->password = "";

         return $user;
      } catch (PDOException $e) {
         echo $e;
      }
   }

   // hash the password (currently uses bcrypt)
   function hashPassword($password)
   {
      return password_hash($password, PASSWORD_DEFAULT);
   }

    // verify the password hash
    function verifyPassword($input, $hash)
    {
        return password_verify($input, $hash);
    }

    function signUp(User $user)
    {
        try {
            //inser the user into the table users and return the user
            $stmt = $this->connection->prepare("INSERT INTO users 
        (email, password, role, username, img, phoneNumber, address) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$user->email, $this->hashPassword($user->password), $user->role, $user->username, $user->img, $user->phoneNumber, $user->address]);
        } catch (PDOException $e) {
            echo $e;
        }
    }
    function checkEmailPassword($email, $password)
    {
        // retrieve the user with the given username
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

      $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
      $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("Incorrect email");
        }

        $passwordResult = $this->verifyPassword($password, $user->password);

        if (!$passwordResult) {
            throw new Exception("Incorrect password");
        }

      // do not pass the password hash to the caller
      $user->password = "";
      return $user;
   }
   public function updateResetToken($userId, $token, $expiry)
   {
      try {
         $stmt = $this->connection->prepare('UPDATE users SET token = :token, reset_token_expiry = :expiry WHERE id = :id');
         $stmt->execute(['token' => $token, 'expiry' => $expiry, 'id' => $userId]);
      } catch (PDOException $e) {
         throw new Exception('Error updating reset token: ' . $e->getMessage());
      }
   }

   public function getUserByEmail($email)
   {
      try {
         $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
         $stmt->bindParam(':email', $email);
         $stmt->execute();

         $user = $stmt->fetch(PDO::FETCH_OBJ);

         if (!$user) {
            return null;
         }

         return $user;
      } catch (PDOException $e) {
         echo $e->getMessage();
         return null;
      }
   }
   public function getUserByResetToken($token)
   {
      try {
         $stmt = $this->connection->prepare("SELECT * FROM users WHERE token = :token AND reset_token_expiry > NOW()");
         $stmt->bindParam(':token', $token);
         $stmt->execute();

         $user = $stmt->fetch(PDO::FETCH_OBJ);

         if (!$user) {
            return null;
         }

         return $user;
      } catch (PDOException $e) {
         echo $e->getMessage();
         return null;
      }
   }
   public function updatePassword($token, $password)
   {
      try {
         $user = $this->getUserByResetToken($token);

         if (!$user) {
            throw new Exception('User not found or token expired');
         }

         $hashedPassword = $this->hashPassword($password);
         $stmt = $this->connection->prepare('UPDATE users SET password = :password, token = NULL, reset_token_expiry = NULL WHERE id = :id');
         $stmt->execute(['password' => $hashedPassword, 'id' => $user->id]);
      } catch (PDOException $e) {
         throw new Exception('Failed to update password: ' . $e->getMessage());
      }
   }
}
