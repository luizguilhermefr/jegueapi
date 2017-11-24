<?php

namespace App\Exceptions;

class CannotFindVideoException extends \Exception
{
    protected $message = 'The video couldn\'t be found';
}