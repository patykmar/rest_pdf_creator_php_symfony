<?php

namespace App\Tests\Controller;

use App\Controller\IHttpMethod;
use App\DataFixtures\CompanyFixtures;
use App\Entity\Company;
use App\Mapper\AddressMapper;
use App\Mapper\CompanyMapper;
use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use App\Repository\CompanyRepository;
use App\Tests\Mock\Dto\DtoMock;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends AbstractControllerTest
{
    private const URI = "/company";
    private CompanyRepository $repository;
    private Company $companyEntity;
    /** @psalm-var ArrayCollection<Company> $companyArray */
    private CompanyMapper $mapper;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(CompanyRepository::class);
        $this->companyEntity = $this->entityConstants->getCompanies()->get(0);

        $this->mapper = new CompanyMapper(new AddressMapper());
//        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();
    }


    public function testNewItem()
    {
        $mockCompanyEntity = $this->mapper->toEntity(DtoMock::getCompanyDto());

        $this->expectOnceRunMethodWithAnyParameters($this->repository, self::METHOD_NAME_SAVE);
        $this->methodWithAnyParameterWillReturnObject($this->repository, self::METHOD_NAME_FIND_ONE_BY, $mockCompanyEntity);
        $this->injectMockToSymfonyContext(CompanyRepository::class, $this->repository);

        $jsonData = $this->jsonSerialize(DtoMock::getCompanyDto());

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
        $this->client->request(IHttpMethod::GET, self::URI);
        $this->assertResponseIsSuccessful();

        $deserializedArrayOfCompanyDto = $this->jsonValidateAndDeserialize("App\Model\Dto\CompanyDto[]", $this->client->getResponse());
        $this->assertCount(CompanyFixtures::REFERENCE_COUNT, $deserializedArrayOfCompanyDto);


        $this->assertCount(CompanyFixtures::REFERENCE_COUNT, $deserializedArrayOfCompanyDto);

        foreach ($deserializedArrayOfCompanyDto as $companyDto) {
            $this->assertCompanyDtoFromFixturesDatabase($companyDto);
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
        define("TEST_ENTITY_ID", 1);

        CompanyFixtures::createCompany(TEST_ENTITY_ID);

        $jsonData = $this->jsonSerialize(DtoMock::getCompanyDto(TEST_ENTITY_ID));

        $this->client->request(IHttpMethod::PUT, self::URI . "/" . TEST_ENTITY_ID, [], [], [], $jsonData);
        $this->assertResponseIsSuccessful();

        /** @var CompanyDto $deserializedCompanyDto */
        $deserializedCompanyDto = $this->jsonValidateAndDeserialize(CompanyDto::class, $this->client->getResponse());

        $this->assertInputAndOutputCompanyDto(DtoMock::getCompanyDto(TEST_ENTITY_ID), $deserializedCompanyDto, TEST_ENTITY_ID);
    }

    private function assertEntityToDto(Company $mockEntity, CompanyDto $deserializeResponse): void
    {
        $this->assertNotNull($deserializeResponse);
        $this->assertNull($deserializeResponse->getId());
        $this->assertSame($mockEntity->getName(), $deserializeResponse->getName());
        $this->assertSame($mockEntity->getCompanyId(), $deserializeResponse->getCompanyId());
        $this->assertSame($mockEntity->getVatNumber(), $deserializeResponse->getVatNumber());
        $this->assertSame($mockEntity->getBankAccountNumber(), $deserializeResponse->getBankAccountNumber());
        $this->assertSame($mockEntity->getIban(), $deserializeResponse->getIban());
        $this->assertSame($mockEntity->getSwift(), $deserializeResponse->getSwift());
        $this->assertSame($mockEntity->getSignature(), $deserializeResponse->getSignature());

        $addressDto = $deserializeResponse->getAddress();
        $this->assertSame($mockEntity->getCountry(), $addressDto->getCountry());
        $this->assertSame($mockEntity->getStreet(), $addressDto->getStreet());
        $this->assertSame($mockEntity->getCity(), $addressDto->getCity());
        $this->assertSame($mockEntity->getZipCode(), $addressDto->getZipCode());
    }

    /**
     * Compare two object of {@link CompanyDto} where first argument <b>$inputCompanyDto</b> is object which is
     * using as a parameter to call rest endpoint. Second parameter <b>$outputCompanyDto</b> should be result of
     * application login which returned endpoint after call.
     */
    private function assertInputAndOutputCompanyDto(CompanyDto $inputCompanyDto, CompanyDto $outputCompanyDto, ?int $id): void
    {
        $this->assertNotNull($outputCompanyDto);
        $this->assertSame($outputCompanyDto->getId(), $id);
        $this->assertSame($inputCompanyDto->getName(), $outputCompanyDto->getName());
        $this->assertSame($inputCompanyDto->getCompanyId(), $outputCompanyDto->getCompanyId());
        $this->assertSame($inputCompanyDto->getVatNumber(), $outputCompanyDto->getVatNumber());
        $this->assertSame($inputCompanyDto->getBankAccountNumber(), $outputCompanyDto->getBankAccountNumber());
        $this->assertSame($inputCompanyDto->getIban(), $outputCompanyDto->getIban());
        $this->assertSame($inputCompanyDto->getSwift(), $outputCompanyDto->getSwift());
        $this->assertSame($inputCompanyDto->getSignature(), $outputCompanyDto->getSignature());

        $inputAddressDto = $inputCompanyDto->getAddress();
        $outputAddressDto = $outputCompanyDto->getAddress();
        $this->assertSame($inputAddressDto->getCountry(), $outputAddressDto->getCountry());
        $this->assertSame($inputAddressDto->getStreet(), $outputAddressDto->getStreet());
        $this->assertSame($inputAddressDto->getCity(), $outputAddressDto->getCity());
        $this->assertSame($inputAddressDto->getZipCode(), $outputAddressDto->getZipCode());
    }


    /**
     * Method tested fields which should be setup by {@link CompanyFixtures} in to test database
     */
    private function assertCompanyDtoFromFixturesDatabase(CompanyDto $companyDto): void
    {
        $this->assertIsInt($companyDto->getId());
        $this->assertStringStartsWith("Company", $companyDto->getName());
        $this->assertStringStartsWith("23456789", $companyDto->getCompanyId());
        $this->assertStringStartsWith("12345678", $companyDto->getVatNumber());
        $this->assertStringEndsWith("234-1234567890/1234", $companyDto->getBankAccountNumber());
        $this->assertStringStartsWith("CZ00001234123456789012", $companyDto->getIban());
        $this->assertStringStartsWith("AIRACZPP12345678", $companyDto->getSwift());
        $this->assertStringStartsWith("CZ000012341234567890123", $companyDto->getSignature());

        $this->assertInstanceOf(AddressDto::class, $companyDto->getAddress());

        $addressDto = $companyDto->getAddress();
        $this->assertStringStartsWith("Company", $addressDto->getCountry());
        $this->assertStringStartsWith("Company", $addressDto->getStreet());
        $this->assertStringStartsWith("Company", $addressDto->getCity());
        $this->assertStringStartsWith("Company", $addressDto->getZipCode());
    }
}
