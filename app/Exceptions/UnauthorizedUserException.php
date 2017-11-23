<?php

namespace App\Exceptions;

class UnauthorizedUserException extends \Exception implements JsonReportable
{
    protected $message = 'UNAUTHORIZED_USER';

    public function getHttpStatus()
    {
        return 403;
    }
}