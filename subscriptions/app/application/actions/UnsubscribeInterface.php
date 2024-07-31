<?php

namespace app\application\actions;

use app\application\forms\UnsubscribeForm;

interface UnsubscribeInterface
{
    /**
     * @param UnsubscribeForm $form
     * @return bool
     */
    public function execute(UnsubscribeForm $form): bool;
}
