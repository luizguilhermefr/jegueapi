<?php

namespace App\Exceptions;

class PasswordConfirmationException extends \Exception implements JsonReportable
{
    protected $message = 'INVALID_PASSWORD_CONFIRMATION';

    public function getHttpStatus()
    {
        return 400;
    }
}