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
            $this->respondWithError(500, $e->getMessage());
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
            $this->respondWithError(500, $e->getMessage());
        }
    }

    /**
    * Updates a user with the given id, if the user id in the token matches the user id in the request
    * @author Luko Pecotic
    */
    public function updateUser()
    {
        try {
            $jwt = $this->getBearerToken();
            $key = 'SECRET_KEY';
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $data = $this->createObjectFromPostedJson("Models\\User");

            if ($decoded->data->id == $data->id) {
                $this->service->updateUser($data->id, $data->username, $data->email, $data->phoneNumber, $data->address);
                $this->respond($data);
            } else {
                $this->respondWithError(401, "Unauthorized");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }   
    }

    /**
    * Extracts the JWT token from the Authorization header
    * @return string The JWT token
    * @throws Exception If the Authorization header is not present
    * @author Luko Pecotic
    */
    private function getBearerToken() 
    {
        $headers = getallheaders();
        if (!empty($headers['Authorization'])) {
            list(, $token) = explode(' ', $headers['Authorization']);
            return $token;
        }
        throw new Exception('Missing token');
    }

    /**
    * Changes the password of the user with the given id, if the user id in the token matches the user id in the request
    * @author Luko Pecotic
    */
    public function changePassword()
    {
        try {
            $jwt = $this->getBearerToken();
            $key = 'SECRET_KEY';
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if ($decoded->data->id == $data->id) {
                $this->service->changePassword($data->id, $data->currentPassword, $data->newPassword);
                $this->respond(array("message" => "Password changed successfully"));
            } else {
                $this->respondWithError(401, "Unauthorized");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }   
    }

    /**
    * Uploads a profile picture for the user with the given id, if the user id in the token matches the user id in the request
    * @author Luko Pecotic
    */
    public function uploadProfilePicture()
    {
        try {
            $jwt = $this->getBearerToken();
            $key = 'SECRET_KEY';
            $decoded = JWT::decode($jwt, new Key($key, 'HS256'));

            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if ($decoded->data->id == $data->id) {
                $this->service->uploadProfilePicture($data->id, $data->base64Image);
                $this->respond(array("message" => "Profile picture uploaded successfully"));
            } else {
                $this->respondWithError(401, "Unauthorized");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }   
    }
}
