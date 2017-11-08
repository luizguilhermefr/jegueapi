<?php

namespace App\Exceptions;

class StringLengthException extends \Exception implements JsonReportable
{
    protected $message = 'INVALID_STRING_LENGTH';

    public function getHttpStatus()
    {
        return 400;
    }
}