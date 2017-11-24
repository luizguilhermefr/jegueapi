<?php

namespace App\Exceptions;

class EmptyVideoException extends \Exception implements JsonReportable
{
    protected $message = 'VIDEO_IS_EMPTY';

    public function getHttpStatus()
    {
        return 400;
    }
}