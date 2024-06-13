<?php

namespace app\models\query;

use app\models\Subscription;

/**
 * Class SubscriptionQuery.
 *
 * @package app\models\query
 *
 * @method SubscriptionQuery clear()
 * @method Subscription createModel()
 */
class SubscriptionQuery extends ActiveQuery
{
    /**
     * @param string $email
     * @return Subscription|null
     */
    public function findByEmail(string $email): ?Subscription
    {
        /** @var ?Subscription */
        return $this->andWhere(['email' => $email])->one();
    }

    /**
     * @return SubscriptionQuery
     */
    public function prepareNotSent(): SubscriptionQuery
    {
        $break = (int)getenv("BREAK_BETWEEN_SENDING_EMAIL");
        return $this->andWhere(['<=', 'last_send_at', time() - $break])
            ->orWhere(['IS', 'last_send_at', null]);
    }
}
