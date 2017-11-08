<?php

namespace App\Exceptions;

class InvalidEmailException extends \Exception implements JsonReportable
{
    protected $message = 'INVALID_EMAIL';

    public function getHttpStatus()
    {
        return 400;
    }
}