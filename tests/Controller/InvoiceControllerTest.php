<?php

namespace App\Tests\Controller;

use App\Model\Dto\InvoiceItemDto;
use App\Repository\InvoiceRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class InvoiceControllerTest extends WebTestCase
{
    private array $invoiceZeroVat;
    private Serializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->initializedInvoice();
    }

    public function testPdfInvoice()
    {
        $client = static::createClient();
        $client->setServerParameter('CONTENT_TYPE', 'application/json');
        $client->setServerParameter('HTTP_ACCEPT', 'application/json');

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

    public function testJsonDecode()
    {
        $this->markTestSkipped("Consider if this test is needed");

        $subArray = array(
            $this->generateInvoiceItem("Item 1", 0, 1111.1111, 10),
            $this->generateInvoiceItem("Item 2", 0, 9999.9999, 20),
            $this->generateInvoiceItem("Item 3", 0, 1111.1111, 30),
            $this->generateInvoiceItem("Item 4", 0, 9999.9999, 40),
        );

        $array = [
            "field1" => "Value of field 1",
            "field2" => "Value of field 2",
            "field3" => "Value of field 3",
            "field4" => "Value of field 4",
            "field5" => "Value of field 5",
            "subFields" => $subArray
        ];

        $jsonSerialized = $this->serializer->serialize($array, 'json');

//        $this->assertNotNull($jsonSerialized);
//        $this->assertNotNull($jsonSerialized);
    }


    private function generateCompany(
        string $companyName = "Fake company",
        string $companyId = "123456789",
        string $bankAccountNumber = null,
        string $vatNumber = null,
        string $iban = null,
        string $swift = null
    ): array
    {
        return [
            "name" => $companyName,
            "companyId" => $companyId,
            "vatNumber" => $vatNumber,
            "bankAccountNumber" => $bankAccountNumber,
            "iban" => $iban,
            "swift:" => $swift,
            "address" => $this->generateAddress()
        ];
    }

    private function generateAddress(
        string $country = "Fiction USA",
        string $street = "Fake street 123",
        string $city = "Springfield",
        string $zipCode = "110 00"
    ): array
    {
        return [
            "country" => $country,
            "street" => $street,
            "city" => $city,
            "zipCode" => $zipCode
        ];
    }

    private function generateInvoiceItem(
        string $itemName,
        int    $vat,
        float  $price,
        float  $unitCount
    ): InvoiceItemDto
    {
        $invoiceItemDto = new InvoiceItemDto();
        return $invoiceItemDto
            ->setItemName($itemName)
            ->setVat($vat)
            ->setPrice($price)
            ->setUnitCount($unitCount);
    }

    public function initializedInvoice(): void
    {
//        $supplier = $this->generateCompany("Fake supplier company", "123456789", "123-123456/1234");
//        $subscriber = $this->generateCompany("Fake subscriber company", "234567891", "234-234567/2345");

        $supplier = $this->generateCompany("Fake supplier company");
        $subscriber = $this->generateCompany("Fake subscriber company");

        $invoiceItems = array(
            $this->generateInvoiceItem("Item 1", 0, 1111.1111, 10),
            $this->generateInvoiceItem("Item 2", 0, 9999.9999, 20),
            $this->generateInvoiceItem("Item 3", 0, 1111.1111, 30),
            $this->generateInvoiceItem("Item 4", 0, 9999.9999, 40),
        );

        $this->invoiceZeroVat = [
            "supplier" => $supplier,
            "subscriber" => $subscriber,
            "paymentType" => "transfer",
            "dueDay" => 1234567890,
            "vs" => "12345678",
            "ks" => "0308",
            "currency" => "CZK",
            "invoiceItems" => $invoiceItems
        ];
    }

}
