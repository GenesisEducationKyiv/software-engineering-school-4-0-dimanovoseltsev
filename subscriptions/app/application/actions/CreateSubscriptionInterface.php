<?php

namespace app\application\actions;

use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\CreateCustomerForm;
use app\application\forms\SubscribeForm;
use app\domain\entities\Customer;
use app\domain\entities\Subscription;

interface CreateSubscriptionInterface
{
    /**
     * @param SubscribeForm $form
     * @return Subscription
     */
    public function execute(SubscribeForm $form): Subscription;
}
