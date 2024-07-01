<?php

namespace app\shared\application\forms;

use app\shared\application\interfaces\Errorable;
use app\shared\application\traits\ErrorsTrait;
use app\shared\application\traits\ValidationRulesTrait;

class BaseForm implements Errorable
{
    use ErrorsTrait;
    use ValidationRulesTrait;

    /**
     * @return void
     */
    protected function filterAttributes(): void
    {
    }
}
