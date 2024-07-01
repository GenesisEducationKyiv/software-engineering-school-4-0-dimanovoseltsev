<?php

namespace app\shared\application\traits;

use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

trait ValidationRulesTrait
{
    /**
     * @param int $value
     * @param string $message
     * @return bool
     */
    protected function validateIdInt(int $value, string $message = 'Is not valid'): bool
    {
        Assert::positiveInteger($value, $message);

        return true;
    }

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

    /**
     * @param string|int|null $value
     * @param string $message
     * @return bool
     */
    protected function validateRequired(string|int|null $value, string $message = 'Cannot be blank'): bool
    {
        Assert::notEmpty($value, $message);
        Assert::notNull($value, $message);

        return true;
    }
}
