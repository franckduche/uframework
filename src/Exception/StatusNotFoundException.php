<?php

namespace Exception;

class StatusNotFoundException extends \RuntimeException
{
    public function __construct($message = null, \Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}
