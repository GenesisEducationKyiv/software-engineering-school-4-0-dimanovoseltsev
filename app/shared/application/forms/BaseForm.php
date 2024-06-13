<?php

namespace app\shared\application\forms;

use app\shared\application\traits\ErrorsTrait;
use app\shared\application\traits\ValidationRulesTrait;

class BaseForm
{
    use ErrorsTrait;
    use ValidationRulesTrait;

    /**
     * @return void
     */
    public function filterAttributes(): void
    {
    }

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}
