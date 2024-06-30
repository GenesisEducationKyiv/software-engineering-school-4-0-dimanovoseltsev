<?php

namespace app\application\actions;

use app\application\dto\SearchSubscribersForMailingDto;
use app\application\services\PublisherServiceInterface;
use app\application\services\SubscriptionServiceInterface;
use app\domain\entities\Currency;

class SendEmailsScheduled extends BaseAction implements SendEmailsScheduledInterface
{
    /**
     * @param SubscriptionServiceInterface $service
     * @param PublisherServiceInterface $publisherService
     */
    public function __construct(
        private readonly SubscriptionServiceInterface $service,
        private readonly PublisherServiceInterface $publisherService,
    ) {
    }

    /**
     * @param Currency $currency
     * @return int
     */
    public function execute(Currency $currency): int
    {
        $lastId = 0;
        $limit = 50;
        $count = 0;
        do {
            $subscriptions = $this->service->getNotSent(
                new SearchSubscribersForMailingDto($lastId, $limit)
            );

            if (empty($subscriptions)) {
                break;
            }

            foreach ($subscriptions as $subscription) {
                $this->publisherService->enqueueMessageForSending($subscription, $currency);
                $lastId = (int)$subscription->getId()->value();
                $count++;
            }
        } while (true);

        return $count;
    }
}
