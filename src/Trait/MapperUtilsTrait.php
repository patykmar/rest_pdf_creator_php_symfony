<?php

namespace App\Trait;

use DateTime;
use DateTimeInterface;


trait MapperUtilsTrait
{
    function datetimeToUnixTime(DateTimeInterface $dateTime): int
    {
        return date_timestamp_get($dateTime);
    }

    function unixTimeToDateTime(int $unixTime): DateTime
    {
        return new DateTime("@$unixTime");
    }

    function actualDateTime(): DateTimeInterface
    {
        return new DateTime('now');
    }

    function actualDateTimeAsUnixTime(): int
    {
        return $this->datetimeToUnixTime($this->actualDateTime());
    }

    function getActualYear(): string
    {
        return $this->actualDateTime()->format("Y");
    }

}
