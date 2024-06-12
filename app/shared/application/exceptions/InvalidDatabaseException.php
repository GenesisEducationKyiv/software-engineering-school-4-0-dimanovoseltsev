<?php

namespace app\shared\application\exceptions;

use Exception;
use Throwable;

class InvalidDatabaseException extends Exception implements Throwable
{

    /**
     * InvalidDatabaseException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = "Invalid database", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
