<?php

namespace app\application\actions;

use app\application\exceptions\NotExistException;
use app\domain\entities\Subscription;

interface RetrieveSubscriptionByEmailInterface
{
    /**
     * @param string $email
     * @return Subscription
     * @throws NotExistException
     */
    public function execute(string $email): Subscription;
}
