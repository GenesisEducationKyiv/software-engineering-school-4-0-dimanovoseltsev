<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class NotSupportedException extends Exception implements Throwable
{
    /**
     * NotExistException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Not supported", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
