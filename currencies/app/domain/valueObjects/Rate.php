<?php

namespace app\domain\valueObjects;

use Webmozart\Assert\Assert;

final class Rate implements ValueObjectInterface
{
    /**
     * @param float $value
     */
    public function __construct(private readonly float $value)
    {
        Assert::nullOrFloat($value);
        Assert::greaterThan($value, 0);
    }

    /**
     * @return float
     */
    public function value(): float
    {
        return $this->value;
    }
}
