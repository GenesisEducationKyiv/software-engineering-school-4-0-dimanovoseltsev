<?php

namespace app\application\actions;

use app\application\adapters\EventBusInterface;
use app\application\dto\CreateSubscriptionDto;
use app\application\events\SubscriptionCreateEvent;
use app\application\exceptions\AlreadyException;
use app\application\exceptions\NotValidException;
use app\application\forms\SubscribeForm;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Subscription;

class Subscribe extends BaseAction implements SubscribeInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     * @param EventBusInterface $eventBus
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
        private readonly EventBusInterface $eventBus,
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
        if (!$form->validate() || $form->getEmail() === null) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByEmail($form->getEmail());
        if ($entity !== null) {
            throw new AlreadyException("Already subscribed");
        }

        $entity = $this->service->create(
            new CreateSubscriptionDto($form->getEmail(), $form->getTimestamp())
        );

        $this->eventBus->publish(new SubscriptionCreateEvent($entity));

        return $entity;
    }
}
