<?php

namespace app\application\traits;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

trait ValidationRulesTrait
{

    /**
     * @param string $value
     * @param string $message
     * @return bool
     */
    protected function validateEmail(string $value, string $message = 'Is not a valid email address'): bool
    {
        $explodeEmail = explode('@', $value);
        if (
            !filter_var($value, FILTER_VALIDATE_EMAIL)
            || !isset($explodeEmail[1])
            || !checkdnsrr($explodeEmail[1], 'MX')
        ) {
            throw new InvalidArgumentException($message);
        }

        return true;
    }
}
