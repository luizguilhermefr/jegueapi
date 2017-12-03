<?php

namespace App\Exceptions;

class VideoNotReadyException extends \Exception implements JsonReportable
{
    protected $message = 'VIDEO_NOT_READY_YET';

    public function getHttpStatus()
    {
        return 404;
    }
}