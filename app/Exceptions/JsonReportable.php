<?php

namespace App\Exceptions;

interface JsonReportable
{
    public function getHttpStatus();
}