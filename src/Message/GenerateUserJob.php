<?php

namespace App\Message;

final class GenerateUserJob
{
    /**
     * @var int
     */
    private $limit;

    /**
     * GenerateUser constructor.
     * @param int $limit
     */
    public function __construct($limit = 1000)
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
