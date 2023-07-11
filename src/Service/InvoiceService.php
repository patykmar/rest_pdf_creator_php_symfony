<?php

namespace App\Service;

use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceDto;
use App\Repository\InvoiceEntityRepository;

class InvoiceService
{

    public function __construct(
        private readonly InvoiceMapper           $invoiceMapper,
        private readonly InvoiceEntityRepository $repository
    )
    {
    }

    public function saveEntity(InvoiceDto $invoiceDto): void
    {
        $invoice = $this->invoiceMapper->toInvoiceEntity($invoiceDto);
        $this->repository->save($invoice);
    }

}
