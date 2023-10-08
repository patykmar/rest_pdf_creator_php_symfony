<?php

namespace App\Tests\Service;

use App\Entity\Invoice;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Mapper\InvoiceMapper;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\InvoiceDto;
use App\Repository\InvoiceRepository;
use App\Service\CompanyService;
use App\Service\InvoiceDefaultValuesService;
use App\Service\InvoiceService;
use App\Tests\Mock\Dto\DtoMock;
use DateTime;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{
    private InvoiceRepository $invoiceRepository;
    private CompanyService $companyService;
    private InvoiceService $invoiceService;
    private InvoiceDto $invoiceDto;

    protected function setUp(): void
    {
        parent::setUp();
        $invoiceMapper = new InvoiceMapper(
            new CompanyMapper(new AddressMapper()),
            new InvoiceDefaultValuesService()
        );
        $this->companyService = $this->createMock(CompanyService::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceService = new InvoiceService($invoiceMapper, $this->companyService, $this->invoiceRepository);

        $this->populateInvoiceDtoMinimalWithInvoiceItems();
    }

    private function populateInvoiceDtoMinimalWithInvoiceItems(): void
    {
        $this->companyService
            ->method('getOneDto')
            ->withAnyParameters()
            ->willReturnOnConsecutiveCalls(
                DtoMock::getCompanyDto(),
                DtoMock::getCompanyDto(1)
            );

        $this->invoiceDto = DtoMock::makeMinimalInvoiceDtoWithInvoiceItems();
    }

    public function testSaveEntity()
    {
        $this->invoiceRepository->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $invoiceDataDto = $this->invoiceService->mapDtoToDataDto($this->invoiceDto);
        $this->invoiceService->saveEntity($invoiceDataDto);
    }

    public function testMapDtoToDataDto()
    {
        $this->populateInvoiceDtoMinimalWithInvoiceItems();
        $invoiceDataDto = $this->invoiceService->mapDtoToDataDto($this->invoiceDto);

        $this->assertNotNull($invoiceDataDto);
        $this->assertInstanceOf(InvoiceDataDto::class, $invoiceDataDto);
        $this->assertNull($invoiceDataDto->getCreated());
        $this->assertNull($invoiceDataDto->getDueDay());
        $this->assertNull($invoiceDataDto->getVs());
        $this->assertNull($invoiceDataDto->getKs());
        $this->assertNull($invoiceDataDto->getCurrency());
        $this->assertIsArray($invoiceDataDto->getInvoiceItems()->toArray());
        $this->assertSame(DtoMock::INVOICE_ITEM_COUNT, count($invoiceDataDto->getInvoiceItems()));
    }

    /**
     * This test is about fulfill default values from {@link InvoiceDefaultValuesService}
     */
    public function testMapDtoToEntityMinimalWithInvoiceItems()
    {
        $this->populateInvoiceDtoMinimalWithInvoiceItems();
        $invoice = $this->invoiceService->mapDtoToEntity($this->invoiceDto);

        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $this->assertNull($invoice->getId());
        $this->assertNull($invoice->getDescription());
        $this->assertNotNull($invoice->getPaymentType());
        $this->assertSame(InvoiceDefaultValuesService::PAYMENT_TYPE, $invoice->getPaymentType());

        $this->assertNotNull($invoice->getCreated());
        $this->assertInstanceOf(DateTime::class, $invoice->getCreated());

        $this->assertNotNull($invoice->getDueDay());
        $this->assertSame(InvoiceDefaultValuesService::DUE_DAY, $invoice->getDueDay());

        $this->assertNotNull($invoice->getVs());
        $this->assertIsString($invoice->getVs());

        $this->assertNotNull($invoice->getKs());
        $this->assertSame(InvoiceDefaultValuesService::KS, $invoice->getKs());

        $this->assertNotNull($invoice->getCurrency());
        $this->assertSame(InvoiceDefaultValuesService::CURRENCY, $invoice->getCurrency());
    }
}
