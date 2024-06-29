<?php

namespace app\application\actions;

use app\application\forms\SubscribeForm;
use app\domain\entities\Subscription;

interface SubscribeInterface
{
    /**
     * @param SubscribeForm $form
     * @return Subscription
     */
    public function execute(SubscribeForm $form): Subscription;
}
