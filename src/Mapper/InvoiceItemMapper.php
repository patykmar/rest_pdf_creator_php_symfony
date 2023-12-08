<?php

namespace App\Mapper;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Exceptions\InvalidArgumentException;
use App\Model\Dto\InvoiceItemDto;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class InvoiceItemMapper implements ICrudMapper
{
    public function __construct(
        private readonly AutoMapperInterface $mapper
    )
    {
    }

    /**
     * @param InvoiceItem $entity
     * @return InvoiceItemDto
     * @throws UnregisteredMappingException
     */
    public function toDto($entity): InvoiceItemDto
    {
        return $this->mapper->map($entity, InvoiceItemDto::class);
    }

    /**
     * @param InvoiceItemDto $dto
     * @return InvoiceItem
     * @throws UnregisteredMappingException
     */
    public function toEntity($dto): InvoiceItem
    {
        return $this->mapper->map($dto, InvoiceItem::class);
    }

    /**
     * @param Collection<InvoiceItem> $entities
     * @return Collection<InvoiceItemDto>
     */
    public function toDtoCollection(Collection $entities): Collection
    {
        return new ArrayCollection($this->mapper->mapMultiple($entities, InvoiceItemDto::class));
    }

    /**
     * @param InvoiceItem $entityFromDb
     * @param InvoiceItem $entityFromConsumer
     * @return InvoiceItem
     */
    public function mappingBeforeEditEntity($entityFromDb, $entityFromConsumer): InvoiceItem
    {
        return $entityFromDb
            ->setVat($entityFromConsumer->getVat())
            ->setItemName($entityFromConsumer->getItemName())
            ->setPrice($entityFromConsumer->getPrice())
            ->setUnitCount($entityFromConsumer->getUnitCount());
    }

    /**
     * For a specific collection of InvoiceItems added relation to Invoice
     * @param Collection<InvoiceItem> $invoiceItems
     */
    public function addRelationWithInvoice(Collection $invoiceItems, Invoice $invoice): void
    {
        foreach ($invoiceItems as $invoiceItem) {
            if (!$invoiceItem instanceof InvoiceItem) {
                throw new InvalidArgumentException("Unexpected type");
            }
            $invoiceItem->setInvoice($invoice);
        }
    }
}
