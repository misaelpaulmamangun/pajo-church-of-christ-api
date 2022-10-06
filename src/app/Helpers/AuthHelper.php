<?php

namespace App\Helpers;

class AuthHelper
{
  /**
   * TODO: check if email is valid
   * @param email string
   * @return boolean
   */
  public static function isEmail(string $email)
  {
    return (filter_var($email, FILTER_VALIDATE_EMAIL));
  }

  /**
   * TODO: check if password same as confirm password
   * @param password string
   * @param confirm_password string
   * @return boolean
   */
  public static function isConfirmPassword(string $password, string $confirmPassword)
  {
    return $password == $confirmPassword;
  }

  /**
   * TODO: check if password is greater than or equal to length
   * @param password string
   * @param length int
   * @return boolean
   */
  public static function isPasswordLength(int $length, string $password)
  {
    return strlen($password) >= $length;
  }
}
