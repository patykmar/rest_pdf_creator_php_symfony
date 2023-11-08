<?php

namespace App\Mapper;

use App\Entity\Invoice;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @implements ICrudMapper<Invoice, InvoiceDataDto>
 */
class InvoiceMapper implements ICrudMapper
{
    public function __construct(
        private readonly AutoMapperInterface $autoMapper
    )
    {
    }

    /**
     * @psalm-param InvoiceDto $dto
     * @throws UnregisteredMappingException
     */
    public function toEntity($dto): Invoice
    {
        return $this->autoMapper->map($dto, Invoice::class);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function dtoToDataDto(InvoiceDto $invoiceDto, CompanyDto $supplier, CompanyDto $subscriber): InvoiceDataDto
    {
        /** @var InvoiceDataDto $invoiceDataDto */
        $invoiceDataDto = $this->autoMapper->map($invoiceDto, InvoiceDataDto::class);
        $invoiceDataDto->setSupplier($supplier);
        $invoiceDataDto->setSubscriber($subscriber);
        return $invoiceDataDto;
    }

    /**
     * @psalm-param Invoice $entity
     * @psalm-return InvoiceDataDto
     * @throws UnregisteredMappingException
     */
    public function toDto($entity): InvoiceDataDto
    {
        return $this->autoMapper->map($entity, InvoiceDataDto::class);
    }

    public function toDtoCollection(Collection $entities): Collection
    {
        return new ArrayCollection($this->autoMapper->mapMultiple($entities, InvoiceDataDto::class));
    }

    /**
     * @param Invoice $entityFromDb
     * @param Invoice $entityFromConsumer
     */
    public function mappingBeforeEditEntity($entityFromDb, $entityFromConsumer): Invoice
    {
        return $entityFromDb
            ->setSupplier($entityFromConsumer->getSupplier())
            ->setSubscriber($entityFromConsumer->getSubscriber())
            ->setVs($entityFromConsumer->getVs())
            ->setKs($entityFromConsumer->getKs())
            ->setDescription($entityFromConsumer->getDescription())
            ->setCurrency($entityFromConsumer->getCurrency())
            ->setPaymentType($entityFromConsumer->getPaymentType())
            ->setDueDay($entityFromConsumer->getDueDay())
            ->setInvoiceItems($entityFromConsumer->getInvoiceItems());
    }
}
