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
     * @psalm-param InvoiceDataDto $dto
     * @throws UnregisteredMappingException
     */
    public function toEntity($dto): Invoice
    {
        return $this->toEntityStrict($dto);
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function toEntityStrict(InvoiceDataDto $invoiceDto): Invoice
    {
        return $this->autoMapper->map($invoiceDto, Invoice::class);
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
        return $this->toDtoStrict($entity);
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function toDtoStrict(Invoice $invoice): InvoiceDataDto
    {
        return $this->autoMapper->map($invoice, InvoiceDataDto::class);
    }

    public function toDtoCollection(Collection $entities): ArrayCollection
    {
        return new ArrayCollection($this->autoMapper->mapMultiple($entities, InvoiceDataDto::class));
    }
}
