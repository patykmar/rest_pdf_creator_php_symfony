<?php

namespace App\Tests\Mapper;

use App\Config\Mapper\InvoiceMapperConfig;
use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceItemDto;
use App\Tests\Mock\Dto\DtoMock;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceMapperITest extends KernelTestCase
{
    private InvoiceMapper $invoiceMapper;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->invoiceMapper = $container->get(InvoiceMapper::class);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testInvoiceDataDtoToEntity()
    {
        $invoiceDataDto = DtoMock::getInvoiceDataDto();
        $result = $this->invoiceMapper->toInvoice($invoiceDataDto);

        $this->assertInstanceOf(Invoice::class, $result);
        $this->assertSame($invoiceDataDto->getPaymentType(), $result->getPaymentType());
        $this->assertSame($invoiceDataDto->getCreated(), date_timestamp_get($result->getCreated()));
        $this->assertSame($invoiceDataDto->getDueDay(), $result->getDueDay());
        $this->assertNotEmpty($result->getVs());
        $this->assertEquals(InvoiceMapperConfig::INVOICE_VS_LENGTH + 2, strlen($result->getVs()));
        $this->assertSame(strtoupper($invoiceDataDto->getCurrency()), $result->getCurrency());
        $this->assertNotNull($result->getSupplier());
        $this->assertInstanceOf(Company::class, $result->getSupplier());
        $this->assertNotNull($result->getSubscriber());
        $this->assertInstanceOf(Company::class, $result->getSubscriber());

        $invoiceItemEntities = $result->getInvoiceItemEntities();

        $this->assertNotEmpty($invoiceItemEntities);

        for ($i = 0; $i < count($invoiceItemEntities); $i++) {
            $this->invoiceItemAssertions($invoiceDataDto->getInvoiceItems()[$i], $invoiceItemEntities[$i]);
        }

    }

    private function invoiceItemAssertions(InvoiceItemDto $invoiceItemDto, InvoiceItem $invoiceItemEntity): void
    {
        $this->assertSame($invoiceItemDto->getVat(), $invoiceItemEntity->getVat());
        $this->assertSame($invoiceItemDto->getItemName(), $invoiceItemEntity->getItemName());
        $this->assertSame($invoiceItemDto->getPrice(), $invoiceItemEntity->getPrice());
        $this->assertSame($invoiceItemDto->getUnitCount(), $invoiceItemEntity->getUnitCount());
    }

}