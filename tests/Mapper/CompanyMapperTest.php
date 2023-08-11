<?php

namespace App\Tests\Mapper;

use App\Entity\Company;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Model\Dto\CompanyDto;
use App\Tests\Dto\DtoConstants;
use App\Tests\Entity\EntityConstants;
use PHPUnit\Framework\TestCase;

class CompanyMapperTest extends TestCase
{
    private EntityConstants $entityConstants;
    private DtoConstants $dtoConstants;
    private CompanyMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityConstants = new EntityConstants();
        $this->dtoConstants = new DtoConstants();
        $this->mapper = new CompanyMapper(new AddressMapper());
    }

    public function testToEntity()
    {
        $companyDto = $this->dtoConstants->getCompanyDto();
        $companyEntity = $this->mapper->toEntity($companyDto);

        $this->assertInstanceOf(Company::class, $companyEntity);
        $this->assertSame($companyDto->getCompanyId(), $companyEntity->getCompanyId());
        $this->assertSame($companyDto->getIban(), $companyEntity->getIban());
        $this->assertSame($companyDto->getId(), $companyEntity->getId());
        $this->assertSame($companyDto->getSignature(), $companyEntity->getSignature());
        $this->assertSame($companyDto->getName(), $companyEntity->getName());
        $this->assertSame($companyDto->getVatNumber(), $companyEntity->getVatNumber());
        $this->assertSame($companyDto->getAddress()->getCity(), $companyEntity->getCity());
        $this->assertSame($companyDto->getAddress()->getCountry(), $companyEntity->getCountry());
        $this->assertSame($companyDto->getAddress()->getStreet(), $companyEntity->getStreet());
        $this->assertSame($companyDto->getAddress()->getZipCode(), $companyEntity->getZipCode());
    }

    public function testToDto()
    {
        $companyEntity = $this->entityConstants->createCompany();
        $companyDto = $this->mapper->toDto($companyEntity);

        $this->assertInstanceOf(CompanyDto::class, $companyDto);
        $this->assertSame($companyEntity->getCompanyId(), $companyDto->getCompanyId());
        $this->assertSame($companyEntity->getIban(), $companyDto->getIban());
        $this->assertSame($companyEntity->getId(), $companyDto->getId());
        $this->assertSame($companyEntity->getSignature(), $companyDto->getSignature());
        $this->assertSame($companyEntity->getName(), $companyDto->getName());
        $this->assertSame($companyEntity->getVatNumber(), $companyDto->getVatNumber());
        $this->assertSame($companyEntity->getCity(), $companyDto->getAddress()->getCity());
        $this->assertSame($companyEntity->getCountry(), $companyDto->getAddress()->getCountry());
        $this->assertSame($companyEntity->getStreet(), $companyDto->getAddress()->getStreet());
        $this->assertSame($companyEntity->getZipCode(), $companyDto->getAddress()->getZipCode());
    }
}
