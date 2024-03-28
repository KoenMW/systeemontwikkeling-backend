<?php

namespace Controllers;

class AccessController extends Controller
{
    function get($role)
    {
        $decoded = $this->checkForJwt($role);
        if ($decoded) {
            $this->respond("Access granted");
        }
    }
}
