<?php

namespace Controllers;

use Services\UserService;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login() {

        // read user data from request body

        // get user from db

        // if the method returned false, the username and/or password were incorrect

        // generate jwt

        // return jwt
    }
}
