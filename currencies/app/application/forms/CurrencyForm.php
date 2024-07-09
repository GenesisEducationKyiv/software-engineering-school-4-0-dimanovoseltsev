<?php

namespace app\application\forms;

use app\application\enums\CurrencyIso;
use app\application\interfaces\Errorable;
use app\application\traits\TimestampTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class CurrencyForm extends BaseForm implements FormInterface, Errorable
{
    use TimestampTrait;

    /**
     * @param string|null $code
     * @param float|null $rate
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
    protected function filterAttributes(): void
    {
        parent::filterAttributes();
        if ($this->rate !== null) {
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
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }


    /**
     * @return float|null
     */
    public function getRate(): ?float
    {
        return $this->rate;
    }
}
