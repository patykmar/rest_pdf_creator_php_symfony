<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Mapper\InvoiceMapper;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\InvoiceDto;
use App\Repository\InvoiceRepository;

class InvoiceService
{

    public function __construct(
        private readonly InvoiceMapper     $invoiceMapper,
        private readonly CompanyService    $companyService,
        private readonly InvoiceRepository $repository,
    )
    {
    }

    public function saveEntity(InvoiceDataDto $invoiceDataDto): void
    {
        $invoice = $this->invoiceMapper->toEntity($invoiceDataDto);
        $this->repository->save($invoice);
    }

    public function mapDtoToDataDto(InvoiceDto $invoiceDto): InvoiceDataDto
    {
        $supplier = $this->companyService->getOneDto($invoiceDto->getSupplierId());
        $subscriber = $this->companyService->getOneDto($invoiceDto->getSubscriberId());
        return $this->invoiceMapper->dtoToDataDto($invoiceDto, $supplier, $subscriber);
    }

    public function mapDtoToEntity(InvoiceDto $invoiceDto): Invoice
    {
        $invoiceDataDto = $this->mapDtoToDataDto($invoiceDto);
        return $this->invoiceMapper->toEntity($invoiceDataDto);
    }

}
