<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Mapper\InvoiceMapper;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\InvoiceDto;
use App\Repository\InvoiceRepository;
use AutoMapperPlus\Exception\UnregisteredMappingException;

class InvoiceService
{
    public function __construct(
        private readonly InvoiceMapper     $invoiceMapper,
        private readonly CompanyService    $companyService,
        private readonly InvoiceRepository $repository,
    )
    {
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function saveEntity(InvoiceDataDto $invoiceDataDto): void
    {
        $invoice = $this->invoiceMapper->toInvoice($invoiceDataDto);
        $this->repository->save($invoice);
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function mapDtoToDataDto(InvoiceDto $invoiceDto): InvoiceDataDto
    {
        $supplier = $this->companyService->getOneDto($invoiceDto->getSupplierId());
        $subscriber = $this->companyService->getOneDto($invoiceDto->getSubscriberId());
        return $this->invoiceMapper->dtoToDataDto($invoiceDto, $supplier, $subscriber);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function mapDtoToEntity(InvoiceDto $invoiceDto): Invoice
    {
        $invoiceDataDto = $this->mapDtoToDataDto($invoiceDto);
        return $this->invoiceMapper->toInvoice($invoiceDataDto);
    }

}
