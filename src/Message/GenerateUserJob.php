<?php

namespace App\Message;

final class GenerateUserJob
{
    private int $limit;

    public function __construct(int $limit = 100)
    {
        $this->limit = $limit;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
