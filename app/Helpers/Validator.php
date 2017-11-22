<?php

namespace App\Helpers;

use App\Exceptions\EmailAlreadyTakenException;
use App\Exceptions\InvalidEmailException;
use App\Exceptions\PasswordConfirmationException;
use App\Exceptions\RequiredParameterException;
use App\Exceptions\StringLengthException;
use App\Exceptions\UsernameAlreadyTakenException;
use App\User;

class Validator
{
    /**
     * @param mixed $something
     * @throws RequiredParameterException
     * @return bool
     */
    public static function validateRequired($something)
    {
        if (is_array($something)) {
            foreach ($something as $item) {
                if (is_null($item)) {
                    throw new RequiredParameterException();
                }
            }
        } else {
            if (is_null($something)) {
                throw new RequiredParameterException();
            }
        }

        return true;
    }

    /**
     * @param string $string
     * @param int $min
     * @param int $max
     * @throws StringLengthException
     * @return bool
     */
    public static function validateString($string, $min = 3, $max = 255)
    {
        $len = strlen($string);
        if ($len < $min || $len > $max) {
            throw new StringLengthException();
        }

        return true;
    }

    /**
     * @param string $email
     * @throws EmailAlreadyTakenException
     * @throws InvalidEmailException
     * @throws StringLengthException
     * @return bool
     */
    public static function validateEmail($email)
    {
        self::validateString($email);
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException();
        }
        if (User::emailExists($email)) {
            throw new EmailAlreadyTakenException();
        }

        return true;
    }

    /**
     * @param string $username
     * @throws UsernameAlreadyTakenException
     * @throws StringLengthException
     * @return bool
     */
    public static function validateUsername($username)
    {
        self::validateString($username);
        if (User::usernameExists($username)) {
            throw new UsernameAlreadyTakenException();
        }

        return true;
    }

    /**
     * @param string $password
     * @param string $confirmation
     * @throws PasswordConfirmationException
     * @throws StringLengthException
     * @return bool
     */
    public static function validatePassword($password, $confirmation)
    {
        self::validateString($password);
        if ($password != $confirmation) {
            throw new PasswordConfirmationException();
        }

        return true;
    }
}