<?php

namespace app\subscriptions\application\forms;

use app\shared\application\forms\BaseForm;
use app\shared\application\forms\FormInterface;
use app\shared\application\interfaces\Errorable;
use app\shared\application\traits\TimestampTrait;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class SubscribeForm extends BaseForm implements FormInterface, Errorable
{
    use TimestampTrait;

    /**
     * @param ?string $email
     */
    public function __construct(
        private $email = null,
    ) {
        $this->setCurrentTimestamp();
        $this->filterAttributes();
    }


    /**
     * @return bool
     */
    public function validate(): bool
    {
        $this->clearErrors();

        try {
            $this->validateRequired($this->email);
            Assert::string($this->email);
            $this->validateEmail($this->email);
        } catch (InvalidArgumentException $e) {
            $this->addError('email', $e->getMessage());
        }
        return !$this->hasErrors();
    }

    /**
     * @return ?string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
}
