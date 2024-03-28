<?php

namespace Controllers;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Controller
{
    protected $service;

    /**
     * Check for JWT token
     * @param int $minAllowedRole the minimum role allowed to access the endpoint
     * @return object|void the decoded JWT or void if the token is invalid
     */
    function checkForJwt(int $minAllowedRole = 2)
    {
        // Check for token header
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->respondWithError(401, "No token provided");
            return;
        }

        // Read JWT from header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        // Strip the part "Bearer " from the header
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1];

        if ($jwt) {
            try {
                return $this->checkJwtPerRole($jwt, $minAllowedRole);
            } catch (Exception $e) {
                $this->respondWithError(401, "Invalid token");
                return;
            }
        }
    }

    /**
     * Check JWT token per role
     * @param string $jwt
     * @return object|void the decoded JWT or void if the token is invalid
     */
    function checkJwtPerRole($jwt, $minAllowedRole = 2)
    {
        if ($jwt == null) {
            throw new Exception("No token provided");
        }

        $keys = [];
        for ($i = $minAllowedRole; $i <= 2; $i++) {
            $keys[] = $this->getSecretKey($i);
        }
        $decoded = null;
        foreach ($keys as $key) {
            try {
                $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
                break;
            } catch (Exception $e) {
                continue;
            }
        }
        if (!$decoded) {
            $this->respondWithError(401, "unauthorized");
        }
        return $decoded;
    }

    /**
     * role id to jwt secret key
     * @param int $role
     * @return string
     * @author Koen Wijchers
     */
    protected function getSecretKey($role)
    {
        switch ($role) {
            case 1:
                return parse_ini_file('../.env')["EMPLOYEE_SECRET_KEY"];
            case 2:
                return parse_ini_file('../.env')["ADMIN_SECRET_KEY"];
            default:
                return parse_ini_file('../.env')["USER_SECRET_KEY"];
        }
    }

    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    function createObjectFromPostedJson($className)
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $object = new $className();
        if ($data == null) {
            return $object;
        }
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            $object->{$key} = $value;
        }
        return $object;
    }
}
