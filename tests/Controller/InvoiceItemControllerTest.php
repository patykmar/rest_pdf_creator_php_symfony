<?php

namespace App\Tests\Controller;


use App\Controller\IHttpMethod;
use App\DataFixtures\InvoiceItemFixtures;
use App\Model\Dto\InvoiceItemDto;
use App\Tests\Mapper\Trait\CommonAsserTrait;
use Symfony\Component\HttpFoundation\Response;

class InvoiceItemControllerTest extends AbstractControllerTest
{
    use CommonAsserTrait;

    const URI = "/invoice-item";

    protected function setUp(): void
    {
        parent::setUp();
        $this->uri = self::URI;
    }

    /**
     * Fetch all method is not allowed for Invoice Item, that I expect response
     * with HTTP status 405.
     */
    public function testFetchAll(): void
    {
        $this->client->request(IHttpMethod::GET, self::URI);
        $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Return all invoice items for specific invoice ID.
     * In this scenario is expected, that Invoice with ID = 1 exist.
     */
    public function testFetchAllByValidInvoiceId(): int
    {
        $this->client->request(IHttpMethod::GET, self::URI . '/invoice/1');
        $this->assertResponseIsSuccessful();

        /** @var InvoiceItemDto[] $result */
        $result = $this->jsonValidateAndDeserializeResponse('App\Model\Dto\InvoiceItemDto[]', $this->client->getResponse());
        $this->assertIsArray($result);

        $this->assertCount(InvoiceItemFixtures::REFERENCE_COUNT, $result);

        foreach ($result as $invoiceItemDto) {
            $this->assertInstanceOf(InvoiceItemDto::class, $invoiceItemDto);
            $this->assertInvoiceItemDtoNotNull($invoiceItemDto);
        }

        return $result[0]->getId();
    }

    /**
     * Method test error handling, when Invoice ID doesn't exist
     */
    public function testFetchAllByInvalidInvoiceId()
    {
        $this->client->request(IHttpMethod::GET, self::URI . '/invoice/666');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertStringContainsString("Invoice with ID: 666 is not found", $this->client->getResponse()->getContent());
    }

    /**
     * Method test success result of controller method fetchById in this scenario we will use
     * first item of result, which we've received from the method {@link testFetchAllByValidInvoiceId}
     * @depends testFetchAllByValidInvoiceId
     */
    public function testFetchByValidId(int $invoiceItemId)
    {
        $this->client->request(IHttpMethod::GET, self::URI . "/$invoiceItemId");
        $this->assertResponseIsSuccessful();
        /** @var InvoiceItemDto $result */
        $result = $this->jsonValidateAndDeserializeResponse(InvoiceItemDto::class, $this->client->getResponse());

        $this->assertInstanceOf(InvoiceItemDto::class, $result);
        $this->assertInvoiceItemDtoNotNull($result);
    }

}
