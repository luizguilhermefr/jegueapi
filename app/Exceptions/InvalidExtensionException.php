<?php

namespace App\Exceptions;

class InvalidExtensionException extends \Exception implements JsonReportable
{
    protected $message = 'INVALID_EXTENSION';

    public function getHttpStatus()
    {
        return 400;
    }
}