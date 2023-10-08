<?php

namespace App\Tests\Mapper;

use App\Entity\Company;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Model\Dto\CompanyDto;
use App\Tests\Mock\Dto\DtoMock;
use App\Tests\Mock\Entity\EntityMock;
use PHPUnit\Framework\TestCase;

class CompanyMapperTest extends TestCase
{
    private EntityMock $entityConstants;
    private CompanyMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityConstants = new EntityMock();
        $this->mapper = new CompanyMapper(new AddressMapper());
    }

    public function testToEntity()
    {
        $companyDto = DtoMock::getCompanyDto();
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

        $this->assertCompanyDto($companyEntity, $companyDto);
    }

    public function testToDtoCollections()
    {
        $companiesEntity = $this->entityConstants->getCompanies();
        $companiesDto = $this->mapper->toDtoCollection($companiesEntity);

        $this->assertNotEmpty($companiesDto);
        $itemsCount = count($companiesEntity);

        $this->assertSame($itemsCount, count($companiesDto));

        for ($i = 0; $i < $itemsCount; $i++) {
            $this->assertCompanyDto($companiesEntity[$i], $companiesDto[$i]);
        }
    }

    private function assertCompanyDto(Company $sourceEntity, CompanyDto $destinationDto): void
    {
        $this->assertInstanceOf(CompanyDto::class, $destinationDto);
        $this->assertSame($sourceEntity->getCompanyId(), $destinationDto->getCompanyId());
        $this->assertSame($sourceEntity->getIban(), $destinationDto->getIban());
        $this->assertSame($sourceEntity->getId(), $destinationDto->getId());
        $this->assertSame($sourceEntity->getSignature(), $destinationDto->getSignature());
        $this->assertSame($sourceEntity->getName(), $destinationDto->getName());
        $this->assertSame($sourceEntity->getVatNumber(), $destinationDto->getVatNumber());
        $this->assertSame($sourceEntity->getCity(), $destinationDto->getAddress()->getCity());
        $this->assertSame($sourceEntity->getCountry(), $destinationDto->getAddress()->getCountry());
        $this->assertSame($sourceEntity->getStreet(), $destinationDto->getAddress()->getStreet());
        $this->assertSame($sourceEntity->getZipCode(), $destinationDto->getAddress()->getZipCode());
    }
}
