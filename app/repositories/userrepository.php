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
    public function getUsers($searchEmail = null, $filterRole = null, $sortByCreateDate = 'ASC')
    {
        $query = "SELECT id, email, role, createDate FROM users WHERE 1 = 1";
        $parameters = [];

        if ($searchEmail !== null) {
            $query .= " AND email LIKE :searchEmail";
            $parameters[':searchEmail'] = '%' . $searchEmail . '%';
        }

        if ($filterRole !== null) {
            $query .= " AND role = :filterRole";
            $parameters[':filterRole'] = $filterRole;
        }

        $query .= " ORDER BY createDate " . ($sortByCreateDate === 'DESC' ? 'DESC' : 'ASC');

        try {
            $stmt = $this->connection->prepare($query);
            foreach ($parameters as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Models\\User');
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function deleteUser($id)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Error deleting user: " . $e->getMessage());
        }
    }

}
