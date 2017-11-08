<?php

namespace App\Exceptions;

class InvalidLoginException extends \Exception implements JsonReportable
{
    protected $message = 'INVALID_EMAIL_OR_PASSWORD';

    public function getHttpStatus()
    {
        return 403;
    }
}