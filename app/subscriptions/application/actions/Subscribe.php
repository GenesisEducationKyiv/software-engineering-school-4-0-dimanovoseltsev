<?php

namespace app\subscriptions\application\actions;

use app\shared\application\exceptions\AlreadyException;
use app\shared\application\exceptions\NotValidException;
use app\subscriptions\application\dto\CreateSubscriptionDto;
use app\subscriptions\application\forms\SubscribeForm;
use app\subscriptions\application\services\SubscriptionServiceInterface;
use app\subscriptions\domain\entities\Subscription;

class Subscribe extends BaseAction implements SubscribeInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
    ) {
    }

    /**
     * @param SubscribeForm $form
     * @return Subscription
     * @throws AlreadyException
     * @throws NotValidException
     */
    public function execute(SubscribeForm $form): Subscription
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByEmail($form->getEmail());
        if ($entity !== null) {
            throw new AlreadyException("Already subscribed");
        }

        return $this->service->subscribe(
            new CreateSubscriptionDto(
                $form->getEmail(),
                $form->getTimestamp()
            )
        );
    }
}
