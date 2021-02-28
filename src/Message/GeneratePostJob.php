<?php

namespace App\Message;

final class GeneratePostJob
{
    /**
     * @var int
     */
    private $limit;

    /**
     * GeneratePostJob constructor.
     * @param int $limit
     */
    public function __construct(int $limit = 1000)
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
