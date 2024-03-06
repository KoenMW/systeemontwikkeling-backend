<?php
namespace Services;

use Repositories\UserRepository;

class UserService {

    private $repository;

    function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function checkUsernamePassword($email, $password) {
        return $this->repository->checkUsernamePassword($email, $password);
    }
    
    public function createUser($user){
        $this->repository->createUser($user);
    }
    public function checkEmailPassword($username, $password){
        return $this->repository->checkEmailPassword($username, $password);
    }
}
