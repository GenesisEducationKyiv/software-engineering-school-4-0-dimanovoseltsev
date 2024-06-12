<?php

namespace app\shared\domain\valueObjects;

use Webmozart\Assert\Assert;

final class Timestamp implements ValueObjectInterface
{
    /**
     * @param int|null $value
     */
    public function __construct(private readonly ?int $value)
    {
        Assert::nullOrInteger($this->value);
        if ($this->value !== null) {
            Assert::greaterThan($this->value, 0);
        }
    }

    /**
     * @return mixed|int
     */
    public function value(): mixed
    {
        return $this->value;
    }
}
