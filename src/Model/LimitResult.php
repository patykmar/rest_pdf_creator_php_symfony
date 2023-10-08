<?php

namespace App\Model;

class LimitResult
{
    /**
     * @param int $first
     * @param int $max
     */
    public function __construct(
        private readonly int $first = 0,
        private readonly int $max = 20,
    )
    {
    }

    public function getFirst(): int
    {
        return $this->first;
    }

    public function getMax(): int
    {
        return $this->max;
    }

}
