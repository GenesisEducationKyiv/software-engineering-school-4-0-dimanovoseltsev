<?php

namespace app\currencies\domain\valueObjects;

use app\shared\domain\valueObjects\ValueObjectInterface;
use Webmozart\Assert\Assert;

final class Rate implements ValueObjectInterface
{
    /**
     * @param float|null $value
     */
    public function __construct(private readonly ?float $value)
    {
        Assert::nullOrFloat($value);
        Assert::greaterThan($value, 0);
    }

    /**
     * @return mixed|int
     */
    public function value(): mixed
    {
        return $this->value;
    }
}
