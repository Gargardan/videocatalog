<?php

namespace App\Domain\Exceptions;

class ValidationException extends \Exception
{
    public function __construct($message) {
        parent::__construct($message, 400);
    }
}