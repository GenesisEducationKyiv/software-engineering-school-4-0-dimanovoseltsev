<?php

namespace app\subscriptions\domain\dto;

readonly class SearchSubscribersDto
{
    /**
     * @param int $lastId
     * @param int $limit
     */
    public function __construct(
        private int $lastId,
        private int $limit,
    ) {
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
