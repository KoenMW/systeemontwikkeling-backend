<?php

namespace Repositories;

use PDO;
use PDOException;
use Repositories\Repository;
use Exception;


class UserRepository extends Repository
{
    function checkUsernamePassword($email, $password)
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
    function createUser($user)
    {
        try{
        //inser the user into the table users and return the user
        $stmt = $this->connection->prepare("INSERT INTO users 
        (email, password, role) VALUES (?,?,?)");
        $stmt->execute([$user->email, $this->hashPassword($user->password), $user->role]);
        }
        catch (PDOException $e){
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
}
