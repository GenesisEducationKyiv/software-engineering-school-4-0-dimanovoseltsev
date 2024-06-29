<?php

namespace app\application\traits;

use Webmozart\Assert\Assert;

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
