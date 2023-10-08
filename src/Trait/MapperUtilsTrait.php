<?php

namespace App\Trait;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use ReflectionException;
use ReflectionMethod;

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

    /**
     * @throws ReflectionException
     */
    public function mappingCollection(ArrayCollection $collection, string $type, string $mappingMethod): ArrayCollection
    {
        if ($collection->isEmpty()) {
            return new ArrayCollection();
        }
        $dtoArray = new ArrayCollection();
        $reflectionMapperMethod = new ReflectionMethod($this, $mappingMethod);

        foreach ($collection as $item) {
            if ($item instanceof $type) {
                $dtoArray->add($reflectionMapperMethod->invoke($this, $item));
            }
        }
        return $dtoArray;
    }

}
