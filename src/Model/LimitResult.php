<?php

namespace App\Model;

class LimitResult
{
    /**
     * @param int $first
     * @param int $max
     */
    private function __construct(
        private readonly int $first,
        private readonly int $max,
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

    public static function of(int $first = 0, int $max = 20): LimitResult
    {
        return new LimitResult($first, $max);
    }

}
