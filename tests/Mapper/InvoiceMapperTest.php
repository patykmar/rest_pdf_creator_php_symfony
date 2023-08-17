<?php

namespace App\Tests\Mapper;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItemEntity;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceItemDto;
use App\Service\InvoiceDefaultValuesService;
use App\Tests\Dto\DtoConstants;
use PHPUnit\Framework\TestCase;

class InvoiceMapperTest extends TestCase
{
    private InvoiceMapper $invoiceMapper;
    private DtoConstants $dtoConstants;

    protected function setUp(): void
    {
        parent::setUp();
        $this->invoiceMapper = new InvoiceMapper(
            new CompanyMapper(new AddressMapper()),
            new InvoiceDefaultValuesService()
        );
        $this->dtoConstants = new DtoConstants();
    }

    public function testToInvoiceEntity()
    {
        $invoiceDto = $this->dtoConstants->getInvoiceDataDto();
        $result = $this->invoiceMapper->toEntity($invoiceDto);

        $this->assertInstanceOf(Invoice::class, $result);
        $this->assertSame($invoiceDto->getPaymentType(), $result->getPaymentType());
        $this->assertSame($invoiceDto->getCreated(), date_timestamp_get($result->getCreated()));
        $this->assertSame($invoiceDto->getDueDay(), $result->getDueDay());
        $this->assertNotEmpty($result->getVs());
        $this->assertSame(strtoupper($invoiceDto->getCurrency()), $result->getCurrency());
        $this->assertNotNull($result->getSupplier());
        $this->assertInstanceOf(Company::class, $result->getSupplier());
        $this->assertNotNull($result->getSubscriber());
        $this->assertInstanceOf(Company::class, $result->getSubscriber());

        $invoiceItemEntities = $result->getInvoiceItemEntities();

        $this->assertNotEmpty($invoiceItemEntities);

        for ($i = 0; $i < count($invoiceItemEntities); $i++) {
            $this->invoiceItemAssertions($invoiceDto->getInvoiceItems()[$i], $invoiceItemEntities[$i]);
        }

    }

    public function testToInvoiceItemEntityObject(): void
    {
        //TODO
        $this->markTestSkipped("testToInvoiceItemEntityObject will be created");
    }

    public function testToInvoiceItemEntityArray(): void
    {
        //TODO
        $this->markTestSkipped("testToInvoiceItemEntityArray will be created");
    }

    public function testToInvoiceItemEntityExceptionTesting(): void
    {
        //TODO
        $this->markTestSkipped("testToInvoiceItemEntityExceptionTesting will be created");
    }

    private function invoiceItemAssertions(InvoiceItemDto $invoiceItemDto, InvoiceItemEntity $invoiceItemEntity): void
    {
        $this->assertSame($invoiceItemDto->getVat(), $invoiceItemEntity->getVat());
        $this->assertSame($invoiceItemDto->getItemName(), $invoiceItemEntity->getItemName());
        $this->assertSame($invoiceItemDto->getPrice(), $invoiceItemEntity->getPrice());
        $this->assertSame($invoiceItemDto->getUnitCount(), $invoiceItemEntity->getUnitCount());
    }

}
