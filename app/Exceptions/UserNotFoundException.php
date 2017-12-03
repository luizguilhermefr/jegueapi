<?php

namespace App\Exceptions;

class UserNotFoundException extends \Exception implements JsonReportable
{
    protected $message = 'USER_NOT_FOUND';

    public function getHttpStatus()
    {
        return 404;
    }
}