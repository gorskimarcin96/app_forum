<?php

namespace App\Message;

final class GenerateUserJob
{
    private int $limit;

    /**
     * GenerateUser constructor.
     * @param int $limit
     */
    public function __construct($limit = 100)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }
}
