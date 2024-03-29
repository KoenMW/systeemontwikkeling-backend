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

    public function getUsers()
    {
        try {
            if (!$this->checkForJwt(2)) return;

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
