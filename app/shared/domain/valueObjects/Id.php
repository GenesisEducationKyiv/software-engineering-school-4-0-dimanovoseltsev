<?php

namespace app\shared\domain\valueObjects;

use app\shared\application\traits\ValidationRulesTrait;
use Webmozart\Assert\Assert;

final class Id implements ValueObjectInterface
{
    use ValidationRulesTrait;

    /**
     * @param int|null $value
     */
    public function __construct(private readonly ?int $value)
    {
        Assert::nullOrInteger($this->value);
        if ($this->value !== null) {
            $this->validateIdInt($this->value);
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
