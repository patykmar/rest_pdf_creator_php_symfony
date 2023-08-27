<?php

namespace App\Tests\Controller;

use App\Controller\IHttpMethod;
use App\Entity\Company;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Model\Dto\CompanyDto;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends AbstractControllerTest
{
    private const URI = "/company";
    private CompanyRepository $repository;
    private Company $companyEntity;
    /** @psalm-var ArrayCollection<Company> $companyArray */
    private ArrayCollection $companyArray;
    private CompanyDto $companyDto;
    private CompanyMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(CompanyRepository::class);
        $this->companyEntity = $this->entityConstants->getCompanies()->get(0);
        $this->companyArray = $this->entityConstants->getCompanies();
        $this->companyDto = $this->dtoConstants->getCompanyDto();
        $this->mapper = new CompanyMapper(new AddressMapper());
    }


    public function testNewItem()
    {
        $mockCompanyEntity = $this->mapper->toEntity($this->companyDto);

        $this->expectOnceRunMethodWithAnyParameters($this->repository, self::METHOD_NAME_SAVE);
        $this->methodWithAnyParameterWillReturnObject($this->repository, self::METHOD_NAME_FIND_ONE_BY, $mockCompanyEntity);
        $this->injectMockToSymfonyContext(CompanyRepository::class, $this->repository);

        $jsonData = $this->jsonSerialize($this->companyDto);

        $this->client->request(IHttpMethod::POST, self::URI, [], [], [], $jsonData);
        $this->assertResponseIsSuccessful();

        /** @var CompanyDto $deserializedCompanyDto */
        $deserializedCompanyDto = $this->jsonValidateAndDeserialize(CompanyDto::class, $this->client->getResponse());

        $this->assertEntityToDto($mockCompanyEntity, $deserializedCompanyDto);
    }

    public function testDeleteItem()
    {
        $this->expectOnceRunMethodWithAnyParameters($this->repository, self::METHOD_NAME_REMOVE);
        $this->methodWithAnyParameterWillReturnObject($this->repository, self::METHOD_NAME_FIND, $this->companyEntity);

        $container = static::getContainer();
        $container->set(CompanyRepository::class, $this->repository);

        $this->client->request(IHttpMethod::DELETE, self::URI . '/1');
        $response = $this->client->getResponse();

        $this->assertNotNull($response);
        $this->assertSame(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testFetchAll()
    {
        $this->methodWithAnyParameterWillReturnObject($this->repository, 'findByLimitResult', $this->companyArray);
        $this->injectMockToSymfonyContext(CompanyRepository::class, $this->repository);
        $this->client->request(IHttpMethod::GET, self::URI);
        $this->assertResponseIsSuccessful();

        $deserializedArrayOfCompanyDto = $this->jsonValidateAndDeserialize("App\Model\Dto\CompanyDto[]", $this->client->getResponse());

        $this->assertCount(20, $deserializedArrayOfCompanyDto);
        for ($i = 0; $i < $this->companyArray->count(); $i++) {
            $this->assertEntityToDto($this->companyArray->get($i), $deserializedArrayOfCompanyDto[$i]);
        }
    }

    public function testFetchById()
    {
        $this->methodWithAnyParameterWillReturnObject($this->repository, self::METHOD_NAME_FIND, $this->companyEntity);
        $this->injectMockToSymfonyContext(CompanyRepository::class, $this->repository);


        $this->client->request(IHttpMethod::GET, self::URI . '/1');
        $this->assertResponseIsSuccessful();

        /** @var CompanyDto $deserializedCompanyDto */
        $deserializedCompanyDto = $this->jsonValidateAndDeserialize(CompanyDto::class, $this->client->getResponse());

        $this->assertEntityToDto($this->companyEntity, $deserializedCompanyDto);
    }


    public function testEditItem()
    {
        $this->markTestSkipped("testEditItem will be defined");
    }

    private function assertEntityToDto(Company $mockEntity, CompanyDto $responseDeserialize): void
    {
        $this->assertNotNull($responseDeserialize);
        $this->assertNull($responseDeserialize->getId());
        $this->assertSame($mockEntity->getName(), $responseDeserialize->getName());
        $this->assertSame($mockEntity->getCompanyId(), $responseDeserialize->getCompanyId());
        $this->assertNull($responseDeserialize->getVatNumber());
        $this->assertSame($mockEntity->getBankAccountNumber(), $responseDeserialize->getBankAccountNumber());
        $this->assertSame($mockEntity->getIban(), $responseDeserialize->getIban());
        $this->assertSame($mockEntity->getSwift(), $responseDeserialize->getSwift());
        $this->assertSame($mockEntity->getSignature(), $responseDeserialize->getSignature());

        $addressDto = $responseDeserialize->getAddress();
        $this->assertSame($mockEntity->getCountry(), $addressDto->getCountry());
        $this->assertSame($mockEntity->getStreet(), $addressDto->getStreet());
        $this->assertSame($mockEntity->getCity(), $addressDto->getCity());
        $this->assertSame($mockEntity->getZipCode(), $addressDto->getZipCode());
    }
}
