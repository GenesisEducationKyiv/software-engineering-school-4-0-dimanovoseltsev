<?php


namespace app\subscriptions\application\dto;


readonly class SearchSubscribersForMailingDto
{
    /**
     * @param int $breakBetweenSending
     * @param int $lastId
     * @param int $limit
     */
    public function __construct(
        private int $breakBetweenSending,
        private int $lastId = 0,
        private int $limit = 20,
    ) {
    }

    /**
     * @return int
     */
    public function getBreakBetweenSending(): int
    {
        return $this->breakBetweenSending;
    }

    /**
     * @return int
     */
    public function getLastId(): int
    {
        return $this->lastId;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
