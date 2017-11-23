<?php

namespace App\Exceptions;

class VideoAlreadyUploadedException extends \Exception implements JsonReportable
{
    protected $message = 'VIDEO_ALREADY_UPLOADED';

    public function getHttpStatus()
    {
        return 409;
    }
}