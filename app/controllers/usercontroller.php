<?php

namespace Controllers;


use Services\UserService;
use Exception;
use Firebase\JWT\Key;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    // initialize services
    protected $service;
    function __construct()
    {
        $this->service = new UserService();
    }
    /**
     * Logs in a user with the given email and password and sends a JWT token in the response.
     * @throws Exception If the email or password is missing or incorrect, or a server error occurs.
     * @author Omar Al Sayasna
     */
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
    /**
     * Creates a new user with the given data and sends the user data in the response.
     * @author Omar Al Sayasna
     */
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
    /**
     * Generates a JWT token for the given user.
     * @throws Exception If the user role is invalid or a server error occurs.
     * @author Omar Al Sayasna
     */
    public function generateJwt($user)
    {
        $secret_key = $this->getSecretKey($user->role);

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
                "userId" => $user->id,
                "email" => $user->email,
                "expireAt" => $expire
            );
    }
    /**
     * Gets the secret key for the given user role.
     * @throws Exception If the role is invalid
     * @author Omar Al Sayasna
     */
    public function getUsers()
    {
        try {
            if (!$this->checkForJwt(2))
                return;

            $searchEmail = $_GET['searchEmail'] ?? null;
            $filterRole = $_GET['filterRole'] ?? null;
            $sortByCreateDate = $_GET['sortByCreateDate'] ?? 'ASC';


            $users = $this->service->getUsers($searchEmail, $filterRole, $sortByCreateDate);
            header('Content-Type: application/json');
            echo json_encode($users);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "something went wrong while fetching users"]);
        }
    }

    /**
     * Updates a user with the given id, if the user id in the token matches the user id in the request
     * @author Luko Pecotic
     */
    public function updateUser()
    {
        try {

            $data = $this->createObjectFromPostedJson("Models\\User");


            $decoded = $this->checkForJwt(0);

            if (!$decoded) {
                return;
            }

            switch ($decoded->data->role) {
                case 0:
                    if ($decoded->data->id != $data->id) {
                        $decoded->data->role = -1;
                        $this->respondWithError(401, "Unauthorized");
                        return;
                    }
                    break;
                case 1:
                    if ($decoded->data->id != $data->id) {
                        $decoded->data->role = -1;
                        $this->respondWithError(401, "Unauthorized");
                        return;
                    }
                    break;
                case 2:
                    break;
                default:
                    $this->respondWithError(401, "Unauthorized");
                    return;
            }

            $this->service->updateUser($data);
            $this->respond($data);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }
    }

    /**
     * Changes the password of the user with the given id, if the user id in the token matches the user id in the request
     * @author Luko Pecotic
     */
    public function changePassword()
    {
        try {

            $data = $this->createObjectFromPostedJson("Models\\PasswordChangeDTO");


            $decoded = $this->checkForJwt(0);

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
            $data = $this->createObjectFromPostedJson("Models\\ProfilePictureDTO");

            $decoded = $this->checkForJwt(0);

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
    /**
     * Deletes a user with the given id, if the user id in the token matches the user id in the request
     * @param int $id The id of the user to delete
     * @throws Exception If the id is not provided or a server error occurs
     * @throws Exception If the user id in the token does not match the user id in the request
     * @author Omar Al Sayasna
     */
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
    /**
     * Sends a password reset email to the user with the given email address
     * @throws Exception If the email is not provided or a server error occurs.
     * @author nick
     */
    public function reset()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data) || !isset($data['email'])) {
                throw new Exception('Missing email in request body', 400);
            }

            $email = trim($data['email']);

            $result = $this->service->reset($email);

            $this->sendResponse($result['message']);
        } catch (Exception $e) {
            $this->sendResponse($e->getMessage(), $e->getCode());
        }
    }
    /**
     * Resets the password of the user with the given token
     * @throws Exception If the password or token is not provided or a server error occurs.
     * @author nick
     */
    public function resetpassword()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if (empty($data) || !isset($data['password']) || !isset($data['token'])) {
                throw new exception('missing password or token in request body', 400);
            }
            $password = $data['password'];
            $token = $data['token'];

            $this->service->resetpassword($token, $password);
            $this->sendresponse('password reset successful');
        } catch (exception $e) {
            $this->sendresponse($e->getmessage(), 500);
        }
    }
    /**
     * Sends a response with the given message and status code.
     * @param string $message The message to send in the response.
     * @param int $statuscode The status code to send in the response.
     * @author nick
     */
    private function sendresponse($message, $statuscode = 200)
    {
        http_response_code($statuscode);
        echo json_encode(['message' => $message]);
    }

    /**
     * Fetches a user by their id and sends the user data in the response.
     * @param int $id The id of the user to fetch.
     * @throws Exception If the id is not provided or a server error occurs.
     * @author Luko Pecotic
     */
    public function getUserById($id)
    {
        try {
            if (empty($id)) {
                throw new Exception("User ID is required");
            }

            $decoded = $this->checkForJwt(0);

            if (!$decoded) {
                return;
            }

            switch ($decoded->data->role) {
                case 0:
                    if ($decoded->data->id != $id) {
                        $this->respondWithError(401, "Unauthorized");
                        return;
                    }
                    break;
                case 1:
                    if ($decoded->data->id != $id) {
                        $this->respondWithError(401, "Unauthorized");
                        return;
                    }
                    break;
                case 2:
                    break;
                default:
                    $this->respondWithError(401, "Unauthorized");
                    return;
            }

            $user = $this->service->getUserById($id);

            if ($user) {
                $this->respond($user);
            } else {
                $this->respondWithError(404, "User not found");
            }
        } catch (Exception $e) {
            $this->respondWithError(500, "Something went wrong while fetching user {$id}");
        }
    }
}
