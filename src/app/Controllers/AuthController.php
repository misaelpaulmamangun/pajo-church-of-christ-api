<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;
use PDO, PDOException;
use \Firebase\JWT\JWT;

class AuthController extends Controller
{
  const USERNAME = "username";
  const PASSWORD = "password";
  const CREATED_AT = "createdAt";

  /**
   * TODO: login user
   * @param request get params
   * @param response json response
   * @return object token and user data
   */
  public function login($request, $response)
  {
    try {
      $sql = "
				SELECT
          id,
          username
				FROM
					users
				WHERE
					username = :%s
				AND
					password = :%s
			";

      $sql = vsprintf($sql, array(self::USERNAME, self::PASSWORD));

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        self::USERNAME => $request->getParam(self::USERNAME),
        self::PASSWORD => $request->getParam(self::PASSWORD)
      ]);

      // Get logged in user's data
      $user = $stmt->fetch(PDO::FETCH_OBJ);

      /* It checks if the user exists in the database. If it doesn't, it returns a 404 error. */
      if (empty($user)) {
        return $response->withJSON([
          'success' => false,
          'status' => 404
        ]);
      }

      $token = [
        'iss' => 'utopian',
        'iat' => time(),
        'exp' => time() + 1000,
        'data' => $user
      ];

      $jwt = JWT::encode($token, $this->c->settings['jwt']['key']);

      return $response->withJson([
        'success' => true,
        'status' => 200,
        'jwt' => $jwt
      ]);
    } catch (PDOException $e) {
      // Catch all database errors
      return $response->withJson([
        'message' => $e->getMessage()
      ]);
    }
  }

  /**
   * TODO: register a new user
   * @param request get params
   * @param response json response
   * @return json
   */
  public function register($request, $response)
  {
    try {
      $user = [
        'username' => $request->getParam(self::USERNAME),
        'password' => $request->getParam(self::PASSWORD),
        'confirmPassword' => $request->getParam('confirmPassword')
      ];
      $passwordMinimumLength = 8;
      $error = false;
      $result = null;

      $sql = "
				INSERT INTO users (
          username,
          password,
          createdAt
        )
				VALUES (
          :%s,
          :%s,
          :%s
        )
			";

      $sql = vsprintf($sql, array(self::USERNAME, self::PASSWORD, self::CREATED_AT));

      /* It checks if the username already exists in the database. */
      if ($this->isUsernameExist($user['username'])) {
        $error = true;
        $result = [
          'message' => 'Username already exists.',
          'status' => 500
        ];
      }

      /* It checks if the password is the same as the confirm password. */
      if (!(AuthHelper::isConfirmPassword($user[self::PASSWORD], $user['confirmPassword']))) {
        $error = true;
        $result = [
          'message' => "Password are not same.",
          'status' => 500
        ];
      }

      /* It checks if the password length is greater than the passed value. */
      if (!(AuthHelper::isPasswordLength($passwordMinimumLength, $user[self::PASSWORD]))) {
        $error = true;
        $result = [
          'message' => 'Password need at least ' . $passwordMinimumLength . ' characters.',
          'status' => 500
        ];
      }

      if ($error) {
        return $response->withJSON($result);
      }

      $stmt = $this->c->db->prepare($sql);

      $stmt->execute([
        self::USERNAME => $user[self::USERNAME],
        self::PASSWORD => $user[self::PASSWORD],
        self::CREATED_AT => date('Y-m-d H:i:s')
      ]);

      return $response->withJSON([
        'message' => 'success',
        'status' => 200
      ]);
    } catch (PDOException $e) {
      return $response->withJSON([
        'message' => $e->getMessage()
      ]);
    }
  }

  /**
   * TODO: It checks if the username already exists in the database.
   * @param string email The email address to check
   * @return a boolean value.
   */
  private function isUsernameExist(string $username)
  {
    $sql = "
			SELECT
				id
			FROM
				users
			WHERE
				username = :%s
		";

    $sql = vsprintf($sql, array(self::USERNAME));

    $stmt = $this->c->db->prepare($sql);

    $stmt->execute([
      self::USERNAME => $username
    ]);

    return $stmt->fetch(PDO::FETCH_OBJ);
  }
}
