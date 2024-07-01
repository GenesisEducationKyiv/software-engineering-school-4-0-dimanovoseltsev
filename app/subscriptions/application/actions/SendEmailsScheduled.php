<?php

namespace app\subscriptions\application\actions;

use app\currencies\domain\entities\Currency;
use app\subscriptions\application\dto\SearchSubscribersForMailingDto;
use app\subscriptions\application\services\PublisherServiceInterface;
use app\subscriptions\application\services\SubscriptionServiceInterface;

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
        $currencyCode = $currency->getIso3()->value();

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
                $this->publisherService->enqueueMessageForSending($subscription->getEmail()->value(), $currencyCode);
                $lastId = (int)$subscription->getId()->value();
                $count++;
            }
        } while (true);

        return $count;
    }
}
