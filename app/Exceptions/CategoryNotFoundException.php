<?php

namespace App\Exceptions;

class CategoryNotFoundException extends \Exception implements JsonReportable
{
    protected $message = 'CATEGORY_NOT_FOUND';

    public function getHttpStatus()
    {
        return 404;
    }
}