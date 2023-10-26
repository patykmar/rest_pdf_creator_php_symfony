<?php

namespace App\Mapper;

use App\Model\Dto\InvoiceItemDto;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;

class InvoiceItemMapper
{

    public function __construct(
        private readonly AutoMapperInterface $mapper
    )
    {
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function toDto($entity)
    {
        return $this->mapper->map($entity, InvoiceItemDto::class);
    }
}
