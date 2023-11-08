<?php

namespace App\Tests\Controller;

use App\Controller\CompanyController;
use App\Controller\IHttpMethod;
use App\DataFixtures\CompanyFixtures;
use App\Mapper\CompanyMapper;
use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CompanyControllerTest extends AbstractControllerTest
{
    protected const URI = "/company";

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = $this->container->get(CompanyMapper::class);
        $this->uri = self::URI;
    }

    public function testNewItem(): int
    {
        $company666 = $this->readJsonFileAndValid('company/newItem666.json');
        $this->requestPost($company666);

        /** @var CompanyDto $company666Dto */
        $company666Dto = $this->jsonValidateAndDeserializeJson(CompanyDto::class, $company666);
        /** @var CompanyDto $deserializedCompanyDto */
        $deserializedCompanyDto = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());

        $this->compareTwoCompanyDto($company666Dto, $deserializedCompanyDto);
        return $deserializedCompanyDto->getId();
    }

    /**
     * Tested method <b>editItem</b> {@link CompanyController}
     * @depends testNewItem
     */
    public function testEditItem(int $id)
    {
        $companyDtoJson = $this->readJsonFileAndValid('company/editItem1.json');
        $this->requestPut($id, $companyDtoJson);

        /** @var CompanyDto $companyDtoExpected */
        $companyDtoExpected = $this->jsonValidateAndDeserializeJson(CompanyDto::class, $companyDtoJson);
        /** @var CompanyDto $companyDtoResult */
        $companyDtoResult = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());

        $this->compareTwoCompanyDto($companyDtoExpected, $companyDtoResult);
    }

    /**
     * @depends testNewItem
     */
    public function testFetchById(int $id)
    {
        $this->requestGetById($id);

        /** @var CompanyDto $deserializedCompanyDtoResponse */
        $deserializedCompanyDtoResponse = $this->jsonValidateAndDeserializeResponse(CompanyDto::class, $this->client->getResponse());
        $this->assertCompanyDtoNotNull($deserializedCompanyDtoResponse);
    }

    /**
     * @depends testNewItem
     */
    public function testDeleteItem(int $id)
    {
        $this->client->request(IHttpMethod::DELETE, self::URI . "/$id");
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

        foreach ($deserializedArrayOfCompanyDto as $companyDto) {
            $this->assertCompanyDtoFromFixturesDatabase($companyDto);
        }
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

        $this->assertSame($expectedCompanyDto->getName(), $resultCompanyDto->getName());

        // address
        $this->assertNotNull($expectedCompanyDto->getAddress());
        $this->assertNotNull($resultCompanyDto->getAddress());
        $this->assertInstanceOf(AddressDto::class, $expectedCompanyDto->getAddress());
        $this->assertInstanceOf(AddressDto::class, $resultCompanyDto->getAddress());

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

