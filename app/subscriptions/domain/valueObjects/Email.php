<?php

namespace app\subscriptions\domain\valueObjects;


use app\shared\application\traits\ValidationRulesTrait;
use app\shared\domain\valueObjects\ValueObjectInterface;

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
     * @return mixed|int
     */
    public function value(): mixed
    {
        return $this->value;
    }
}
