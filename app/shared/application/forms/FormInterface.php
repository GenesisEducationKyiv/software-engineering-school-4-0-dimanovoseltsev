<?php

namespace app\shared\application\forms;

interface FormInterface
{
    /**
     * @return bool
     */
    public function validate(): bool;
}
