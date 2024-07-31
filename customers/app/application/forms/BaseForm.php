<?php

namespace app\application\forms;

use app\application\interfaces\Errorable;
use app\application\traits\ErrorsTrait;
use app\application\traits\ValidationRulesTrait;

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
