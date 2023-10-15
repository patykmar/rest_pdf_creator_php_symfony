<?php

namespace App\Mapper;

use App\Entity\Invoice;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;

class InvoiceMapper
{
    public function __construct(
        private readonly AutoMapperInterface $autoMapper
    )
    {
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function toInvoice(InvoiceDataDto $invoiceDto): Invoice
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
}
