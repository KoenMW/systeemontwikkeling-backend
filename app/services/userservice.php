<?php

namespace Services;

use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct($db)
    {
        $this->repository = new UserRepository($db);
    }

    public function checkUsernamePassword($email, $password) {
        return $this->repository->checkUsernamePassword($email, $password);
    }
    
    public function createUser($user){
        return $this->repository->createUser($user);
    }

    public function checkEmailPassword($email, $password){
        return $this->repository->checkEmailPassword($email, $password);
    }

    public function updateResetToken($userId, $token, $expiry)
    {
        return $this->repository->updateResetToken($userId, $token, $expiry);
    }
    public function getUsers($searchEmail = null, $filterRole = null, $sortByCreateDate = 'ASC') {
        return $this->repository->getUsers($searchEmail, $filterRole, $sortByCreateDate);
    }
}
