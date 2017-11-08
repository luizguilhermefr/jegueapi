<?php

namespace App\Exceptions;

class EmailAlreadyTakenException extends \Exception implements JsonReportable
{
    protected $message = 'EMAIL_ALREADY_TAKEN';

    public function getHttpStatus()
    {
        return 409;
    }
}