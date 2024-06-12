<?php

namespace app\subscriptions\application\actions;

use app\subscriptions\application\forms\SubscribeForm;
use app\subscriptions\domain\entities\Subscription;

interface SubscribeInterface
{
    /**
     * @param SubscribeForm $form
     * @return Subscription
     */
    public function execute(SubscribeForm $form): Subscription;
}
