<?php

namespace app\application\forms;

interface FormInterface
{
    /**
     * @return bool
     */
    public function validate(): bool;
}
