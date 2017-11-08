<?php

namespace App\Exceptions;

class UsernameAlreadyTakenException extends \Exception implements JsonReportable
{
    protected $message = 'USERNAME_ALREADY_TAKEN';

    public function getHttpStatus()
    {
        return 409;
    }
}