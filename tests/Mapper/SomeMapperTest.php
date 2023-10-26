<?php

namespace App\Tests\Mapper;

use App\DataFixtures\CompanyFixtures;
use App\DataFixtures\InvoiceFixtures;
use App\DataFixtures\InvoiceItemFixtures;
use App\Mapper\SomeMapper;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SomeMapperTest extends KernelTestCase
{
    use CommonAsserTrait;

    private SomeMapper $mapper;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();
        $this->mapper = $container->get(SomeMapper::class);
    }

    public function testToDtoCollection(): void
    {

    }

    public function testToEntity(): void
    {

    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testToDto(): void
    {
        $fakeInvoice = InvoiceFixtures::createEntity(1, CompanyFixtures::createCompany(), CompanyFixtures::createCompany(2));
        $invoiceItemEntity = InvoiceItemFixtures::creatEntity(1, $fakeInvoice);
        $invoiceItemDto = $this->mapper->toDto($invoiceItemEntity);

        $this->assertSame($invoiceItemEntity->getId(), $invoiceItemDto->getId());
        $this->assertSame($invoiceItemEntity->getVat(), $invoiceItemDto->getVat());
        $this->assertSame($invoiceItemEntity->getItemName(), $invoiceItemDto->getItemName());
        $this->assertSame($invoiceItemEntity->getPrice(), $invoiceItemDto->getPrice());
        $this->assertSame($invoiceItemEntity->getUnitCount(), $invoiceItemDto->getUnitCount());
    }
}
