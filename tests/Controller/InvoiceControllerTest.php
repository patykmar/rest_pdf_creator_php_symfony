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
use App\Tests\Mapper\CommonAsserTrait;
use App\Tests\Mock\Dto\DtoMock;

class InvoiceControllerTest extends AbstractControllerTest
{
    use CommonAsserTrait;

    const URI = "/invoice";
    private array $invoiceZeroVat;

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

    public function testFetchById(): void
    {
        $this->client->request(IHttpMethod::GET, self::URI . '/1');
        $this->assertResponseIsSuccessful();
        /** @var InvoiceDataDto $invoiceDataDto */
        $invoiceDataDto = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());
        $this->assertInvoiceNotNull($invoiceDataDto);
    }

    /**
     * Tested method <b>editItem</b> from class {@link InvoiceController}
    */
    public function testEdit(): void
    {
        $jsonContent6 = $this->readJsonFileAndValid('invoice/invoiceNoVat_id_6.json');
        /** @var InvoiceDto $invoiceDtoJson6 */
        $invoiceDtoJson6 = $this->jsonValidateAndDeserializeJson(InvoiceDto::class, $jsonContent6);
        $this->client->request(IHttpMethod::PUT, self::URI . '/1', [], [], [], $jsonContent6);
        $this->assertResponseIsSuccessful();

        /** @var InvoiceDataDto $invoiceDataDto */
        $invoiceDataDto = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());
        $this->assertInvoiceNotNull($invoiceDataDto);

        $this->assertSame(1, $invoiceDataDto->getId());
        $this->assertInvoiceDtoAndInvoiceDataDto($invoiceDtoJson6, $invoiceDataDto);

        // changing back invoice with ID 1
        $invoiceDto1 = DtoMock::getInvoiceDto();
        $invoiceDtoJson1 = $this->jsonSerialize($invoiceDto1);

        $this->client->request(IHttpMethod::PUT, self::URI . '/1', [], [], [], $invoiceDtoJson1);

        /** @var InvoiceDataDto $invoiceDataDto1 */
        $invoiceDataDto1 = $this->jsonValidateAndDeserializeResponse(InvoiceDataDto::class, $this->client->getResponse());
        $this->assertInvoiceNotNull($invoiceDataDto1);
        $this->assertSame(1, $invoiceDataDto1->getId());
        $this->assertInvoiceDtoAndInvoiceDataDto($invoiceDto1, $invoiceDataDto1);
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
        $this->assertSame($invoiceDto->getCreated(), $invoiceDataDto->getCreated());
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
            $invoiceItemDtos = [
                $invoiceDto->getInvoiceItems()[$i],
                $invoiceDataDto->getInvoiceItems()[$i]
            ];
            $this->assertInvoiceItemDtos($invoiceItemDtos);
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
     * @param array $invoiceItemDtos expected two items
     * 1. item source (expected value)
     * 2. item result of api endpoint
     */
    public function assertInvoiceItemDtos(array $invoiceItemDtos): void
    {
        $this->assertCount(2, $invoiceItemDtos);

        $invoiceItemDtoExpected = $invoiceItemDtos[0];
        $this->assertInstanceOf(InvoiceItemDto::class, $invoiceItemDtoExpected);

        $invoiceItemDtoResult = $invoiceItemDtos[1];
        $this->assertInstanceOf(InvoiceItemDto::class, $invoiceItemDtoResult);

        $this->assertIsNumeric($invoiceItemDtoResult->getId());
        $this->assertSame($invoiceItemDtoExpected->getVat(), $invoiceItemDtoResult->getVat());
        $this->assertSame($invoiceItemDtoExpected->getItemName(), $invoiceItemDtoResult->getItemName());
        $this->assertSame($invoiceItemDtoExpected->getPrice(), $invoiceItemDtoResult->getPrice());
        $this->assertSame($invoiceItemDtoExpected->getUnitCount(), $invoiceItemDtoResult->getUnitCount());
    }

}
