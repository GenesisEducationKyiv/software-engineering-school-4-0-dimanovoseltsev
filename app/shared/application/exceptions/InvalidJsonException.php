<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidJsonException.
 */
class InvalidJsonException extends Exception implements Throwable
{
    /**
     * InvalidJsonException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Invalid json", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
