<?php

namespace App\Tests\Mock\Dto;

use App\Model\Dto\InvoiceItemDto;
use App\Trait\MockUtilsTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InvoiceItemDtoMock
{
    use MockUtilsTrait;

    public const COUNT = 10;

    public static function createDtoZeroVat(int $id = 1): InvoiceItemDto
    {
        $invoiceItemDto = new InvoiceItemDto();
        return $invoiceItemDto
            ->setId($id)
            ->setPrice(100.0 * $id)
            ->setItemName("Invoice item dto name " . self::twoDigitWithZero($id))
            ->setUnitCount(10.0 * $id)
            ->setVat(0);
    }

    public static function createDtoZeroVatCollection(): Collection
    {
        $result = new ArrayCollection();
        for ($i = 1; $i <= self::COUNT; $i++) {
            $result->add(self::createDtoZeroVat($i));
        }
        return $result;
    }
}
