<?php

namespace app\currencies\application\forms;

use app\currencies\application\enums\CurrencyIso;
use app\shared\application\forms\BaseForm;
use app\shared\application\forms\FormInterface;
use app\shared\application\traits\TimestampTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class CreateCurrencyForm extends BaseForm implements FormInterface
{
    use TimestampTrait;

    /**
     * @param $code
     * @param $rate
     */
    public function __construct(
        private $code = null,
        private $rate = null,
    ) {
        $this->setCurrentTimestamp();

        $this->filterAttributes();
    }

    /**
     * @return void
     */
    public function filterAttributes(): void
    {
        if (is_string($this->rate)) {
            $this->rate = (float)$this->rate;
        }

        if ($this->code !== null) {
            $this->code = (string)$this->code;
        }
    }


    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->clearErrors();

        try {
            $this->validateRequired($this->code);
            Assert::string($this->code);

            $enum = CurrencyIso::tryFrom($this->code);
            if ($enum === null) {
                throw new InvalidArgumentException(sprintf("Currency %s is not supported", $this->code));
            }
        } catch (InvalidArgumentException $e) {
            $this->addError('code', $e->getMessage());
        }

        try {
            Assert::float($this->rate);
            Assert::greaterThan($this->rate, 0);
        } catch (InvalidArgumentException $e) {
            $this->addError('rate', $e->getMessage());
        }
        return !$this->hasErrors();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }


    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }
}
