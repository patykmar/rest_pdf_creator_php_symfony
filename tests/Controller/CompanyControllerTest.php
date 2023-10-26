<?php

namespace App\Tests\Controller;

use App\Controller\CompanyController;
use App\Controller\IHttpMethod;
use App\DataFixtures\CompanyFixtures;
use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use App\Repository\CompanyRepository;
use App\Tests\Mock\Dto\DtoMock;
use AutoMapperPlus\Exception\UnregisteredMappingException;
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

        $this->mapper = $this->container->get(CompanyMapper::class);
//        $this->databaseTool = $this->container->get(DatabaseToolCollection::class)->get();
    }


    /**
     * @throws UnregisteredMappingException
     */
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
        $deserializedCompanyDto = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());

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

        $deserializedArrayOfCompanyDto = $this->jsonValidateAndDeserializeResponse("App\Model\Dto\CompanyDto[]", $this->client->getResponse());
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

        /** @var CompanyDto $deserializedCompanyDtoResponse */
        $deserializedCompanyDtoResponse = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());
        $this->assertEntityToDto($this->companyEntity, $deserializedCompanyDtoResponse);

    }

    /**
     * Tested method <b>editItem</b> {@link CompanyController}
     * Update company with id 1, tested output and do rollback 2nd call
     */
    public function testEditItem()
    {
        $jsonContent666 = $this->readJsonFileAndValid('company/companyEdit666.json');
        /** @var CompanyDto $expectedCompanyDto666 */
        $expectedCompanyDto666 = $this->jsonValidateAndDeserializeJson(CompanyDto::class, $jsonContent666);
        $this->client->request(IHttpMethod::PUT, self::URI . '/1', [], [], [], $jsonContent666);
        $this->assertResponseIsSuccessful();
        /** @var CompanyDto $deserializedCompanyDto666 */
        $deserializedCompanyDto666 = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());
        $this->compareTwoCompanyDto($expectedCompanyDto666, $deserializedCompanyDto666);

        // Rollback company content
        $jsonContent1 = $this->readJsonFileAndValid('company/companyEdit1.json');
        /** @var CompanyDto $expectedCompanyDto1 */
        $expectedCompanyDto1 = $this->jsonValidateAndDeserializeJson(CompanyDto::class, $jsonContent1);
        $this->client->request(IHttpMethod::PUT, self::URI . '/1', [], [], [], $jsonContent1);
        $this->assertResponseIsSuccessful();
        /** @var CompanyDto $deserializedCompanyDto1 */
        $deserializedCompanyDto1 = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());
        $this->compareTwoCompanyDto($expectedCompanyDto1, $deserializedCompanyDto1);

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
    private function compareTwoCompanyDto(CompanyDto $expectedCompanyDto, CompanyDto $resultCompanyDto): void
    {
        $this->assertNotNull($expectedCompanyDto);
        $this->assertNotNull($resultCompanyDto);

        $this->assertNull($expectedCompanyDto->getId());
        $this->assertIsNumeric($resultCompanyDto->getId());
        $this->assertSame(1, $resultCompanyDto->getId());

        $this->assertSame($expectedCompanyDto->getName(), $resultCompanyDto->getName());

        // address
        $this->assertNotNull($expectedCompanyDto->getAddress());
        $this->assertNotNull($resultCompanyDto->getAddress());
        $this->assertSame($expectedCompanyDto->getAddress()->getCountry(), $resultCompanyDto->getAddress()->getCountry());
        $this->assertSame($expectedCompanyDto->getAddress()->getStreet(), $resultCompanyDto->getAddress()->getStreet());
        $this->assertSame($expectedCompanyDto->getAddress()->getCity(), $resultCompanyDto->getAddress()->getCity());
        $this->assertSame($expectedCompanyDto->getAddress()->getZipCode(), $resultCompanyDto->getAddress()->getZipCode());

        $this->assertSame($expectedCompanyDto->getCompanyId(), $resultCompanyDto->getCompanyId());
        $this->assertSame($expectedCompanyDto->getVatNumber(), $resultCompanyDto->getVatNumber());
        $this->assertSame($expectedCompanyDto->getBankAccountNumber(), $resultCompanyDto->getBankAccountNumber());
        $this->assertSame($expectedCompanyDto->getSwift(), $resultCompanyDto->getSwift());
        $this->assertSame($expectedCompanyDto->getIban(), $resultCompanyDto->getIban());
        $this->assertSame($expectedCompanyDto->getSignature(), $resultCompanyDto->getSignature());
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
        $this->assertNotNull($companyDto->getBankAccountNumber());
        $this->assertSame(21, strlen($companyDto->getBankAccountNumber()));
        $this->assertStringStartsWith("CZ00001234123456789012", $companyDto->getIban());
        $this->assertStringStartsWith("AIRACZPP12345678", $companyDto->getSwift());
        $this->assertStringStartsWith("CZ000012341234567890123", $companyDto->getSignature());

        $this->assertInstanceOf(AddressDto::class, $companyDto->getAddress());

        $addressDto = $companyDto->getAddress();
        $this->assertStringStartsWith("Company", $addressDto->getCountry());
        $this->assertStringStartsWith("Company", $addressDto->getStreet());
        $this->assertStringStartsWith("Company", $addressDto->getCity());
        $this->assertStringStartsWith("123", $addressDto->getZipCode());
    }
}

