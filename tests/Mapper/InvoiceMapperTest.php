<?php

namespace App\Tests\Mapper;

use App\Config\Mapper\InvoiceMapperConfig;
use App\DataFixtures\CompanyFixtures;
use App\DataFixtures\InvoiceFixtures;
use App\DataFixtures\InvoiceItemFixtures;
use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Mapper\InvoiceMapper;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\InvoiceItemDto;
use App\Tests\AbstractKernelTestCase;
use App\Tests\Mapper\Trait\CommonAsserTrait;
use App\Tests\Mock\Dto\DtoMock;
use App\Trait\MapperUtilsTrait;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Exception;

class InvoiceMapperTest extends AbstractKernelTestCase
{
    use MapperUtilsTrait, CommonAsserTrait;

    private InvoiceMapper $mapper;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = $this->container->get(InvoiceMapper::class);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testInvoiceDataDtoToEntity()
    {
        $invoiceDataDto = DtoMock::getInvoiceDataDto();
        $resultInvoice = $this->mapper->toEntity($invoiceDataDto);

        $this->assertInstanceOf(Invoice::class, $resultInvoice);
        $this->assertSame($invoiceDataDto->getPaymentType(), $resultInvoice->getPaymentType());
        $this->assertSame($invoiceDataDto->getCreated(), date_timestamp_get($resultInvoice->getCreated()));
        $this->assertSame($invoiceDataDto->getDueDay(), $resultInvoice->getDueDay());
        $this->assertNotEmpty($resultInvoice->getVs());
        $this->assertEquals(InvoiceMapperConfig::INVOICE_VS_LENGTH + 2, strlen($resultInvoice->getVs()));
        $this->assertSame(strtoupper($invoiceDataDto->getCurrency()), $resultInvoice->getCurrency());

        $supplier = $resultInvoice->getSupplier();
        $this->assertNotNull($supplier);
        $this->assertInstanceOf(Company::class, $supplier);
        $this->assertCompanyDtoToCompany($invoiceDataDto->getSupplier(), $supplier);

        $subscriber = $resultInvoice->getSubscriber();
        $this->assertNotNull($subscriber);
        $this->assertInstanceOf(Company::class, $subscriber);
        $this->assertCompanyDtoToCompany($invoiceDataDto->getSubscriber(), $subscriber);

        $invoiceItemEntities = $resultInvoice->getInvoiceItems();

        $this->assertNotEmpty($invoiceItemEntities);

        for ($i = 0; $i < count($invoiceItemEntities); $i++) {
            $this->invoiceItemAssertions($invoiceDataDto->getInvoiceItems()[$i], $invoiceItemEntities[$i]);
        }

    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testInvoiceToInvoiceDataDto()
    {
        $sourceInvoice = InvoiceFixtures::createEntity(1, CompanyFixtures::createCompany(), CompanyFixtures::createCompany(2));
        for ($i = 0; $i < InvoiceItemFixtures::REFERENCE_COUNT; $i++) {
            $sourceInvoice->addInvoiceItemEntity(InvoiceItemFixtures::creatEntity($i, $sourceInvoice));
        }

        $resultInvoiceDataDto = $this->mapper->toDto($sourceInvoice);

        $this->assertInstanceOf(InvoiceDataDto::class, $resultInvoiceDataDto);
        $this->assertSame($sourceInvoice->getId(), $resultInvoiceDataDto->getId());
        $this->assertSame($sourceInvoice->getDescription(), $resultInvoiceDataDto->getDescription());
        $this->assertSame($sourceInvoice->getPaymentType(), $resultInvoiceDataDto->getPaymentType());
        $this->assertSame($this->datetimeToUnixTime($sourceInvoice->getCreated()), $resultInvoiceDataDto->getCreated());
        $this->assertSame($sourceInvoice->getDueDay(), $resultInvoiceDataDto->getDueDay());
        $this->assertSame($sourceInvoice->getVs(), $resultInvoiceDataDto->getVs());
        $this->assertSame($sourceInvoice->getKs(), $resultInvoiceDataDto->getKs());
        $this->assertSame($sourceInvoice->getCurrency(), $resultInvoiceDataDto->getCurrency());
        $this->assertSameSize($sourceInvoice->getInvoiceItems(), $resultInvoiceDataDto->getInvoiceItems());
        $this->assertNotNull($resultInvoiceDataDto->getSupplier());
        $this->assertNotNull($resultInvoiceDataDto->getSubscriber());
        $this->assertCompanyToCompanyDto($sourceInvoice->getSupplier(), $resultInvoiceDataDto->getSupplier());
        $this->assertCompanyToCompanyDto($sourceInvoice->getSubscriber(), $resultInvoiceDataDto->getSubscriber());

        for ($i = 0; $i < count($sourceInvoice->getInvoiceItems()); $i++) {
            $this->invoiceItemAssertions(
                $resultInvoiceDataDto->getInvoiceItems()[$i],
                $sourceInvoice->getInvoiceItems()[$i]
            );
        }

    }

    private function invoiceItemAssertions(InvoiceItemDto $sourceInvoiceItemDto, InvoiceItem $resultInvoiceItemEntity): void
    {
        $this->assertNotNull($resultInvoiceItemEntity);
        $this->assertNotNull($sourceInvoiceItemDto);
        $this->assertSame($sourceInvoiceItemDto->getId(), $resultInvoiceItemEntity->getId());
        $this->assertSame($sourceInvoiceItemDto->getVat(), $resultInvoiceItemEntity->getVat());
        $this->assertSame($sourceInvoiceItemDto->getItemName(), $resultInvoiceItemEntity->getItemName());
        $this->assertSame($sourceInvoiceItemDto->getPrice(), $resultInvoiceItemEntity->getPrice());
        $this->assertSame($sourceInvoiceItemDto->getUnitCount(), $resultInvoiceItemEntity->getUnitCount());
    }

}