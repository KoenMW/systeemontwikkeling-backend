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
    public function deleteUser($id)
    {
        return $this->repository->deleteUser($id);
    }
}
