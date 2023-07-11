<?php

namespace App\Tests\Mapper;

use App\Entity\Invoice;
use App\Entity\InvoiceItemEntity;
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
        $this->invoiceMapper = new InvoiceMapper(new InvoiceDefaultValuesService());
        $this->dtoConstants = new DtoConstants();
    }


    public function testToInvoiceEntity()
    {
        $invoiceDto = $this->dtoConstants->getInvoiceDto();
        $result = $this->invoiceMapper->toInvoiceEntity($invoiceDto);

        $this->assertInstanceOf(Invoice::class, $result);
        $this->assertSame($invoiceDto->getPaymentType(), $result->getPaymentType());
        $this->assertSame($invoiceDto->getCreated(), date_timestamp_get($result->getCreated()));
        $this->assertSame($invoiceDto->getDueDay(), $result->getDueDay());
        $this->assertNotEmpty($result->getVs());
        $this->assertSame(strtoupper($invoiceDto->getCurrency()), $result->getCurrency());

        $this->assertSame($invoiceDto->getSupplier()->getName(), $result->getSupplierName());
        $this->assertSame($invoiceDto->getSupplier()->getCompanyId(), $result->getSupplierCompanyId());
        $this->assertSame($invoiceDto->getSupplier()->getVatNumber(), $result->getSupplierVatNumber());
        $this->assertSame($invoiceDto->getSupplier()->getBankAccountNumber(), $result->getSupplierBankAccountNumber());
        $this->assertSame($invoiceDto->getSupplier()->getSwift(), $result->getSupplierSwift());
        $this->assertSame($invoiceDto->getSupplier()->getAddress()->getCountry(), $result->getSupplierAddressCountry());
        $this->assertSame($invoiceDto->getSupplier()->getAddress()->getStreet(), $result->getSupplierAddressStreet());
        $this->assertSame($invoiceDto->getSupplier()->getAddress()->getCity(), $result->getSupplierAddressCity());
        $this->assertSame($invoiceDto->getSupplier()->getAddress()->getZipCode(), $result->getSupplierAddressZipCode());

        $this->assertSame($invoiceDto->getSubscriber()->getName(), $result->getSubscriberName());
        $this->assertSame($invoiceDto->getSubscriber()->getCompanyId(), $result->getSubscriberCompanyId());
        $this->assertSame($invoiceDto->getSubscriber()->getVatNumber(), $result->getSubscriberVatNumber());
        $this->assertSame($invoiceDto->getSubscriber()->getBankAccountNumber(), $result->getSubscriberBankAccountNumber());
        $this->assertSame($invoiceDto->getSubscriber()->getSwift(), $result->getSubscriberSwift());
        $this->assertSame($invoiceDto->getSubscriber()->getAddress()->getCountry(), $result->getSubscriberAddressCountry());
        $this->assertSame($invoiceDto->getSubscriber()->getAddress()->getStreet(), $result->getSubscriberAddressStreet());
        $this->assertSame($invoiceDto->getSubscriber()->getAddress()->getCity(), $result->getSubscriberAddressCity());
        $this->assertSame($invoiceDto->getSubscriber()->getAddress()->getZipCode(), $result->getSubscriberAddressZipCode());

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
