<?php

namespace app\subscriptions\domain\valueObjects;

use app\application\traits\ValidationRulesTrait;
use app\domain\valueObjects\ValueObjectInterface;

final class Email implements ValueObjectInterface
{
    use ValidationRulesTrait;

    /**
     * @param string $value
     */
    public function __construct(private readonly string $value)
    {
        $this->validateEmail($this->value);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        return $this->value;
    }
}
