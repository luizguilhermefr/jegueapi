<?php

namespace App\Exceptions;

class RequiredParameterException extends \Exception implements JsonReportable
{
    protected $message = 'REQUIRED_PARAMETER';

    public function getHttpStatus()
    {
        return 400;
    }
}