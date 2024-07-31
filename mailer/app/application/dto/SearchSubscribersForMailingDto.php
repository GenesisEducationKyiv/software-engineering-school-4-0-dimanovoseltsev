<?php

namespace app\application\dto;

readonly class SearchSubscribersForMailingDto
{
    /**
     * @param int $lastId
     * @param int $limit
     */
    public function __construct(
        private int $lastId = 0,
        private int $limit = 20,
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
