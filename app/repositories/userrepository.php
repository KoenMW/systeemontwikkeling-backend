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
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
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
            error_log($e->getMessage());
            throw new Exception($e->getMessage());
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
        try {
            $sql = "UPDATE users SET";
            if ($user->role != -1) {
                $sql .= " role = :role,";
            }
            if (isset($user->username) && $user->username) {
                $sql .= " username = :username,";
            }
            if (isset($user->email) && $user->email) {
                $sql .= " email = :email,";
            }
            if (isset($user->phoneNumber) && $user->phoneNumber) {
                $sql .= " phoneNumber = :phoneNumber,";
            }
            if (isset($user->address) && $user->address) {
                $sql .= " address = :address,";
            }

            $sql = rtrim($sql, ',');
            $sql .= " WHERE id = :id";

            $stmt = $this->connection->prepare($sql);

            if ($user->role != -1) {
                $stmt->bindParam(':role', $user->role);
            }
            if (isset($user->username) && $user->username) {
                $stmt->bindParam(':username', $user->username);
            }
            if (isset($user->email) && $user->email) {
                $stmt->bindParam(':email', $user->email);
            }
            if (isset($user->phoneNumber) && $user->phoneNumber) {
                $stmt->bindParam(':phoneNumber', $user->phoneNumber);
            }
            if (isset($user->address) && $user->address) {
                $stmt->bindParam(':address', $user->address);
            }

            $stmt->bindParam(':id', $user->id);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateResetToken($userId, $token, $expire)
    {
        try {
            $stmt = $this->connection->prepare('UPDATE users SET token = :token, reset_token_expire = :expire WHERE id = :id');
            $stmt->execute(['token' => $token, 'expire' => $expire, 'id' => $userId]);
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
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE token = :token AND reset_token_expire > NOW()");
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
            $stmt = $this->connection->prepare('UPDATE users SET password = :password, token = NULL, reset_token_expire = NULL WHERE id = :id');
            $stmt->execute(['password' => $hashedPassword, 'id' => $user->id]);
        } catch (PDOException $e) {
            throw new Exception('Failed to update password: ' . $e->getMessage());
        }
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
        try {
            $stmt = $this->connection->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
            $user = $stmt->fetch();

            $result = $this->verifyPassword($currentPassword, $user->password);

            if (!$result) {
                throw new Exception("Incorrect current password");
            }

            $newPasswordHash = $this->hashPassword($newPassword);

            $stmt = $this->connection->prepare("UPDATE users SET password = :password WHERE id = :id");
            $stmt->bindParam(':password', $newPasswordHash);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
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
        try {
            $decodedImage = base64_decode($base64Image);

            $stmt = $this->connection->prepare("UPDATE users SET img = :img WHERE id = :id");
            $stmt->bindParam(':img', $decodedImage, PDO::PARAM_LOB);
            $stmt->bindParam(':id', $id);

            $stmt->execute();
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

    /**
     * Fetches a user from the database by their id.
     * @param int $id The id of the user to fetch.
     * @return User The fetched user.
     * @throws Exception If no user is found or a database error occurs.
     * @author Luko Pecotic
     */
    public function getUserById($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
            $user = $stmt->fetch();

            if (!$user) {
                throw new Exception("User not found");
            }

            if ($user->img) {
                $user->img = base64_encode($user->img);
            }

            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
