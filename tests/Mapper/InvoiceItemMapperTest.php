<?php

namespace App\Tests\Mapper;

use App\DataFixtures\CompanyFixtures;
use App\DataFixtures\InvoiceFixtures;
use App\DataFixtures\InvoiceItemFixtures;
use App\Mapper\InvoiceItemMapper;
//use App\Tests\AbstractKernelTestCase;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InvoiceItemMapperTest extends KernelTestCase
{
//    private InvoiceItemMapper $mapper;

    /**
     * @throws Exception
     */
//    protected function setUp(): void
//    {
//        parent::setUp();
//        $this->mapper = $this->container->get(InvoiceItemMapper::class);
//    }

    /**
     * @throws UnregisteredMappingException
     * @throws Exception
     */
    public function testToDto(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $mapper = $container->get(InvoiceItemMapper::class);

        $fakeInvoice = InvoiceFixtures::createEntity(1, CompanyFixtures::createCompany(), CompanyFixtures::createCompany(2));
        $invoiceItemEntity = InvoiceItemFixtures::creatEntity(1, $fakeInvoice);
//        $invoiceItemDto = $this->mapper->toDto($invoiceItemEntity);
        $invoiceItemDto = $mapper->toDto($invoiceItemEntity);

        $this->assertSame($invoiceItemEntity->getId(), $invoiceItemDto->getId());
        $this->assertSame($invoiceItemEntity->getVat(), $invoiceItemDto->getVat());
        $this->assertSame($invoiceItemEntity->getItemName(), $invoiceItemDto->getItemName());
        $this->assertSame($invoiceItemEntity->getPrice(), $invoiceItemDto->getPrice());
        $this->assertSame($invoiceItemEntity->getUnitCount(), $invoiceItemDto->getUnitCount());

    }


}
