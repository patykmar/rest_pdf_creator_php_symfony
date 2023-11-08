<?php

namespace App\Config\Mapper;

use App\Entity\InvoiceItem;
use App\Model\Dto\InvoiceItemDto;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\DataType;

class InvoiceItemMapperConfig implements AutoMapperConfiguratorInterface
{
    /**
     * @inheritDoc
     */
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(InvoiceItemDto::class, InvoiceItem::class);
        $config->registerMapping(InvoiceItemDto::class, InvoiceItemDto::class);
        $config->registerMapping(DataType::ARRAY, InvoiceItemDto::class);
        $config->registerMapping(InvoiceItem::class, InvoiceItemDto::class);
    }
}
