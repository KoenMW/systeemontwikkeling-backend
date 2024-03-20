<?php

namespace Controllers;


use Services\UserService;
use Exception;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;


class UserController extends Controller
{
    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        try {
            $data = $this->createObjectFromPostedJson("Models\\User");
            $user = $this->service->checkEmailPassword($data->email, $data->password);

            $tokenResponse = $this->generateJwt($user);
            $this->respond($tokenResponse);
        } catch (Exception $e) {
            $this->respondWithError(500, "Invalid email or password");
        }
    }
    public function createUser()
    {
        try {
            $user = $this->createObjectFromPostedJson("Models\\User");
            $this->service->createUser($user);
            $this->respond($user);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }
    public function generateJwt($user)
    {
        $secret_key = 'SECRET_KEY';

        $issuer = "THE_ISSUER"; // this can be the domain/servername that issues the token
        $audience = "THE_AUDIENCE"; // this can be the domain/servername that checks the token

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before 

        $expire = $issuedAt + 9000;
        $token_payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->id,
                "email" => $user->email,
                "role" => $user->role
            )
        );

        // Encode the JWT token
        $jwt = JWT::encode($token_payload, $secret_key, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "email" => $user->email,
                "expireAt" => $expire
            );
    }
    public function getUsers()
    {
        try {
            $searchEmail = $_GET['searchEmail'] ?? null;
            $filterRole = $_GET['filterRole'] ?? null;
            $sortByCreateDate = $_GET['sortByCreateDate'] ?? 'ASC';

            $users = $this->service->getUsers($searchEmail, $filterRole, $sortByCreateDate);
            header('Content-Type: application/json');
            $this->respond($users);
        } catch (Exception $e) {
            $this->respondWithError(500, "something went wrong while fetching users");
        }
    }
    public function deleteUser($id)
    {
        try {
            if (empty($id)) {
                throw new Exception("User ID is required");
            }

            $result = $this->service->deleteUser($id);

            if ($result) {
                $this->respond(['message' => "User deleted successfully"]);
            } else {
                $this->respondWithError(404, "User not found");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, "something went wrong while deleting user {$id}");
        }
    }

}
