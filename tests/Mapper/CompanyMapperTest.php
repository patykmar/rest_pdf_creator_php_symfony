<?php

namespace App\Tests\Mapper;

use App\DataFixtures\CompanyFixtures;
use App\Mapper\CompanyMapper;
use App\Tests\AbstractKernelTestCase;
use App\Tests\Mapper\Trait\CommonAsserTrait;
use App\Tests\Mock\Dto\DtoMock;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;

class CompanyMapperTest extends AbstractKernelTestCase
{
    use CommonAsserTrait;

    private CompanyMapper $mapper;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = $this->container->get(CompanyMapper::class);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testToEntity()
    {
        $companyDto = DtoMock::getCompanyDto();
        $companyEntity = $this->mapper->toEntity($companyDto);

        $this->assertCompanyDtoToCompany($companyDto, $companyEntity);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function testToDto()
    {
        $companyEntity = CompanyFixtures::createCompany();
        $companyDto = $this->mapper->toDto($companyEntity);

        $this->assertCompanyToCompanyDto($companyEntity, $companyDto);
    }

    public function testToDtoCollections()
    {
        $companiesEntity = $this->createCompanyCollection();
        $companiesDto = $this->mapper->toDtoCollection($companiesEntity);

        $this->assertNotEmpty($companiesDto);
        $itemsCount = count($companiesEntity);

        $this->assertSame($itemsCount, count($companiesDto));

        for ($i = 0; $i < $itemsCount; $i++) {
            $this->assertCompanyToCompanyDto($companiesEntity[$i], $companiesDto[$i]);
        }
    }


    private function createCompanyCollection(): Collection
    {
        $result = new ArrayCollection();
        for ($i = 1; $i <= CompanyFixtures::REFERENCE_COUNT; $i++) {
            $result->add(CompanyFixtures::createCompany($i));
        }
        return $result;
    }
}
