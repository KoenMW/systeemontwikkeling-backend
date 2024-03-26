<?php

namespace Services;

use Models\User;
use Repositories\UserRepository;

class UserService
{

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
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
    public function checkEmailPassword($username, $password)
    {
        return $this->repository->checkEmailPassword($username, $password);
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
    public function updateUser($id, $username, $email, $phoneNumber, $address)
    {
        $this->repository->updateUser($id, $username, $email, $phoneNumber, $address);
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
}
