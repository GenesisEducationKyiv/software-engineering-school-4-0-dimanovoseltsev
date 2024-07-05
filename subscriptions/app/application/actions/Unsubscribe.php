<?php

namespace app\application\actions;

use app\application\adapters\EventBusInterface;
use app\application\events\SubscriptionDeleteEvent;
use app\application\exceptions\NotExistException;
use app\application\exceptions\NotValidException;
use app\application\forms\UnsubscribeForm;
use app\application\services\SubscriptionServiceInterface;

class Unsubscribe extends BaseAction implements UnsubscribeInterface
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
     * @param UnsubscribeForm $form
     * @return bool
     * @throws NotValidException
     * @throws NotExistException
     */
    public function execute(UnsubscribeForm $form): bool
    {
        if (!$form->validate()) {
            throw new NotValidException($form->getErrors());
        }

        $entity = $this->service->getByEmail($form->getEmail());
        $entity = $this->checkExit($entity);

        $deleted = $this->service->delete($form->getEmail());
        if ($deleted > 0) {
            $this->eventBus->publish(new SubscriptionDeleteEvent($entity));
        }

        return true;
    }
}
