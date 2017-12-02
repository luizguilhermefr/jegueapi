<?php

namespace App\Exceptions;

class UnparseableVideoException extends \Exception
{
    protected $message = 'The video couldn\'t be parsed';
}