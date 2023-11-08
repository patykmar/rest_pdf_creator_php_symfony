<?php

namespace App\Tests\Controller;

use App\Controller\IHttpMethod;
use App\Controller\InvoiceController;
use App\DataFixtures\InvoiceFixtures;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use App\Repository\InvoiceRepository;
use App\Tests\Mapper\Trait\CommonAsserTrait;

class InvoiceControllerTest extends AbstractControllerTest
{
    use CommonAsserTrait;

    const URI = "/invoice";
    private array $invoiceZeroVat;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uri = self::URI;
    }

    public function testNewItem(): int
    {
        $invoiceJson = $this->readJsonFileAndValid('invoice/newItemInvoiceNoVat.json');
        $this->requestPost($invoiceJson);

        /** @var InvoiceDto $invoiceDto input values */
        $invoiceDto = $this->jsonValidateAndDeserializeJson(InvoiceDto::class, $invoiceJson);
        /** @var InvoiceDataDto $deserializedInvoiceDto deserialized result */
        $deserializedInvoiceDto = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());

        $this->assertInvoiceDtoAndInvoiceDataDto($invoiceDto, $deserializedInvoiceDto);
        return $deserializedInvoiceDto->getId();
    }

    /**
     * @depends testNewItem
     */
    public function testFetchById(int $id): void
    {
        $this->client->request(IHttpMethod::GET, self::URI . "/$id");
        $this->assertResponseIsSuccessful();
        /** @var InvoiceDataDto $invoiceDataDto */
        $invoiceDataDto = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());
        $this->assertInvoiceNotNull($invoiceDataDto);
    }


    public function testFetchAll()
    {
        $this->client->request(IHttpMethod::GET, self::URI);
        $this->assertResponseIsSuccessful();

        /** @var InvoiceDataDto[] $deserializedResult */
        $deserializedResult = $this->jsonValidateAndDeserializeResponse("App\Model\DataDto\InvoiceDataDto[]", $this->client->getResponse());

        $this->assertCount(InvoiceFixtures::REFERENCE_COUNT, $deserializedResult);

        foreach ($deserializedResult as $item) {
            $this->assertInvoiceNotNull($item);
        }
    }


    /**
     * Tested method <b>editItem</b> from class {@link InvoiceController}
     * @depends testNewItem
     */
    public function testEdit(int $id): void
    {
        $jsonContent6 = $this->readJsonFileAndValid('invoice/invoiceNoVat_id_6.json');
        /** @var InvoiceDto $invoiceDtoJson6 */
        $invoiceDtoJson6 = $this->jsonValidateAndDeserializeJson(InvoiceDto::class, $jsonContent6);
        $this->requestPut($id, $jsonContent6);
        $this->assertResponseIsSuccessful();

        /** @var InvoiceDataDto $invoiceDataDto */
        $invoiceDataDto = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());
        $this->assertInvoiceNotNull($invoiceDataDto);

        $this->assertSame($id, $invoiceDataDto->getId());
        $this->assertInvoiceDtoAndInvoiceDataDto($invoiceDtoJson6, $invoiceDataDto);
    }

    /**
     * @depends testNewItem
     */
    public function testDeleteItem(int $id): void
    {
        $this->requestDelete($id);
    }

    public function testPdfInvoice()
    {
        $this->markTestSkipped("The test will be fixed by new PR");

        $repository = $this->createMock(InvoiceRepository::class);

        $repository->expects($this->once())
            ->method('save')
            ->withAnyParameters();

        $container = static::getContainer();
        // inject mock
        $container->set(InvoiceRepository::class, $repository);

        $jsonData = $this->serializer->serialize($this->invoiceZeroVat, 'json');
        $client->request('POST', '/invoice/data', [], [], [], $jsonData);
        $this->assertResponseIsSuccessful();

    }

    /**
     * @param InvoiceDto $invoiceDto source DTO object
     * @param InvoiceDataDto $invoiceDataDto destination DataDto object from response
     */
    public function assertInvoiceDtoAndInvoiceDataDto(InvoiceDto $invoiceDto, InvoiceDataDto $invoiceDataDto): void
    {
        $this->assertIsNumeric($invoiceDataDto->getId());
        $this->assertSame($invoiceDto->getPaymentType(), $invoiceDataDto->getPaymentType());
        $this->assertNotEmpty($invoiceDto->getCreated());
        $this->assertNotEmpty($invoiceDataDto->getCreated());
        $this->assertSame($invoiceDto->getDueDay(), $invoiceDataDto->getDueDay());
        $this->assertSame($invoiceDto->getVs(), $invoiceDataDto->getVs());
        $this->assertSame($invoiceDto->getKs(), $invoiceDataDto->getKs());
        $this->assertSame($invoiceDto->getCurrency(), $invoiceDataDto->getCurrency());

        $this->assertCompanyDto($invoiceDto->getSupplierId(), $invoiceDataDto->getSupplier());
        $this->assertCompanyDto($invoiceDto->getSubscriberId(), $invoiceDataDto->getSubscriber());

        $countOfSourceInvoiceItems = count($invoiceDto->getInvoiceItems());
        $countOfResultInvoiceItems = count($invoiceDataDto->getInvoiceItems());

        $this->assertSame($countOfSourceInvoiceItems, $countOfResultInvoiceItems);

        for ($i = 0; $i < $countOfSourceInvoiceItems; $i++) {
            $this->assertInvoiceItemDtos(
                $invoiceDto->getInvoiceItems()[$i],
                $invoiceDataDto->getInvoiceItems()[$i]
            );
            unset($invoiceItemDtos);
        }
    }

    private function assertCompanyDto(int $companyId, CompanyDto $companyDto): void
    {
        $this->assertNotNull($companyDto);
        $this->assertCompanyDtoNotNull($companyDto);
        $this->assertSame($companyId, $companyDto->getId());
    }

    /**
     * Compare two same data type {@see InvoiceItemDto} if there is same value
     */
    public function assertInvoiceItemDtos(InvoiceItemDto $invoiceItemDtoExpected, InvoiceItemDto $invoiceItemDtoResult): void
    {
        $this->assertIsNumeric($invoiceItemDtoResult->getId());
        $this->assertSame($invoiceItemDtoExpected->getVat(), $invoiceItemDtoResult->getVat());
        $this->assertSame($invoiceItemDtoExpected->getItemName(), $invoiceItemDtoResult->getItemName());
        $this->assertSame($invoiceItemDtoExpected->getPrice(), $invoiceItemDtoResult->getPrice());
        $this->assertSame($invoiceItemDtoExpected->getUnitCount(), $invoiceItemDtoResult->getUnitCount());
    }

}
