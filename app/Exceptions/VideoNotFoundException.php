<?php

namespace App\Exceptions;

class VideoNotFoundException extends \Exception implements JsonReportable
{
    protected $message = 'VIDEO_NOT_FOUND';

    public function getHttpStatus()
    {
        return 404;
    }
}