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
use App\Tests\Dto\DtoConstants;
use DateTime;
use PHPUnit\Framework\TestCase;

class InvoiceServiceTest extends TestCase
{

    private DtoConstants $dtoConstants;
    private InvoiceRepository $invoiceRepository;
    private CompanyService $companyService;
    private InvoiceService $invoiceService;
    private InvoiceDto $invoiceDto;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dtoConstants = new DtoConstants();

        $invoiceMapper = new InvoiceMapper(
            new CompanyMapper(new AddressMapper()),
            new InvoiceDefaultValuesService()
        );
        $this->companyService = $this->createMock(CompanyService::class);
        $this->invoiceRepository = $this->createMock(InvoiceRepository::class);
        $this->invoiceService = new InvoiceService($invoiceMapper, $this->companyService, $this->invoiceRepository);
    }

    public function testSaveEntity()
    {
        $this->populateInvoiceDtoMinimalWithInvoiceItems();

        $this->invoiceRepository->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $invoiceDataDto = $this->invoiceService->mapDtoToDataDto($this->invoiceDto);
        $this->invoiceService->saveEntity($invoiceDataDto);
    }

    private function populateInvoiceDtoMinimalWithInvoiceItems(): void
    {
        $this->companyService
            ->method('getOneDto')
            ->withAnyParameters()
            ->willReturnOnConsecutiveCalls(
                $this->dtoConstants->getCompaniesDto()[0],
                $this->dtoConstants->getCompaniesDto()[1]
            );

        $this->invoiceDto = $this->dtoConstants->makeMinimalInvoiceDtoWithInvoiceItems(1, 2);
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
        $this->assertIsArray($invoiceDataDto->getInvoiceItems());
        $this->hasSize(5);
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
