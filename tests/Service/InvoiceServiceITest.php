<?php

namespace App\Tests\Service;

use App\Config\Mapper\InvoiceMapperConfig;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use App\Service\InvoiceService;
use App\Tests\Mock\Dto\DtoMock;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceServiceITest extends KernelTestCase
{
    private InvoiceService $invoiceService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->invoiceService = $container->get(InvoiceService::class);
    }

    /**
     * Test mapper from InvoiceDto to Invoice with specify all InvoiceDto field
     * @throws UnregisteredMappingException
     */
    public function testMappingInvoiceDtoToToInvoice(): void
    {
        $invoiceDto = DtoMock::getInvoiceDto();
        $result = $this->invoiceService->mapDtoToEntity($invoiceDto);

        $this->assertInvoice($result, $invoiceDto);
    }

    /**
     * Test mapper from InvoiceDto to Invoice with minimum InvoiceDto field specify
     * @throws UnregisteredMappingException
     */
    public function testMinimalInvoiceWithItemsAsArray(): void
    {
        $invoiceDto = DtoMock::makeMinimalInvoiceWithItemsAsArray();
        $result = $this->invoiceService->mapDtoToEntity($invoiceDto);
        $this->assertInvoice($result, $invoiceDto, InvoiceMapperConfig::PAYMENT_TYPE, InvoiceMapperConfig::KS);
    }

    /**
     * @param Invoice $invoice result after mapping
     * @param InvoiceDto $source define input for mapping
     * @param string $paymentType 
     * @param string $ks
     */
    private function assertInvoice(Invoice $invoice, InvoiceDto $source, string $paymentType = 'cache', string $ks = "0001"): void
    {
        $this->assertNotNull($invoice);
        $this->assertInstanceOf(Invoice::class, $invoice);

        $this->assertNull($invoice->getId());
        $this->assertNull($invoice->getDescription());
        $this->assertNotNull($invoice->getPaymentType());
        $this->assertSame($paymentType, $invoice->getPaymentType());

        $this->assertNotNull($invoice->getCreated());
        $this->assertInstanceOf(DateTime::class, $invoice->getCreated());

        $this->assertNotNull($invoice->getDueDay());
        $this->assertSame(InvoiceMapperConfig::DUE_DAY, $invoice->getDueDay());

        $this->assertNotNull($invoice->getVs());
        $this->assertIsString($invoice->getVs());

        $this->assertNotNull($invoice->getKs());
        $this->assertSame($ks, $invoice->getKs());

        $this->assertNotNull($invoice->getCurrency());
        $this->assertSame(InvoiceMapperConfig::CURRENCY, $invoice->getCurrency());

        $invoiceItemEntities = $invoice->getInvoiceItems();

        $this->assertNotEmpty($invoiceItemEntities);

        for ($i = 0; $i < count($invoiceItemEntities); $i++) {
            $this->invoiceItemAssertions($source->getInvoiceItems()[$i], $invoiceItemEntities[$i]);
        }

    }

    /**
     * Method which compare values from source data type to map result
     * @param InvoiceItemDto|array $invoiceItem source data
     * @param InvoiceItem $invoiceItemEntity values after mapping
     */
    private function invoiceItemAssertions(InvoiceItemDto|array $invoiceItem, InvoiceItem $invoiceItemEntity): void
    {
        if (is_array($invoiceItem)) {
            $this->assertSame($invoiceItem['vat'], $invoiceItemEntity->getVat());
            $this->assertSame($invoiceItem['itemName'], $invoiceItemEntity->getItemName());
            $this->assertSame($invoiceItem['price'], $invoiceItemEntity->getPrice());
            $this->assertSame($invoiceItem['unitCount'], $invoiceItemEntity->getUnitCount());
        } else {
            $this->assertSame($invoiceItem->getVat(), $invoiceItemEntity->getVat());
            $this->assertSame($invoiceItem->getItemName(), $invoiceItemEntity->getItemName());
            $this->assertSame($invoiceItem->getPrice(), $invoiceItemEntity->getPrice());
            $this->assertSame($invoiceItem->getUnitCount(), $invoiceItemEntity->getUnitCount());
        }
    }
}
