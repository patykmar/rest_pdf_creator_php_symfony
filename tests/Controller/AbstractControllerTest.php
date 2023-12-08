<?php

namespace App\Tests\Controller;

use App\Controller\IHttpMethod;
use App\Tests\Controller\trait\JsonTestUtilsTrait;
use App\Tests\Controller\trait\MockTrait;
use App\Tests\Mapper\Trait\CommonAsserTrait;
use App\Tests\Mock\Entity\EntityMock;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AbstractControllerTest extends WebTestCase
{
    use MockTrait;
    use JsonTestUtilsTrait;
    use CommonAsserTrait;

    public const JSON = "json";

    protected Serializer $serializer;
    protected KernelBrowser $client;
    protected EntityMock $entityConstants;
    protected ContainerInterface $container;
    protected string $uri;

    protected function setUp(): void
    {
        parent::setUp();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [
            new JsonSerializableNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(null, null, null, new ReflectionExtractor()),
        ];
        $this->serializer = new Serializer($normalizers, $encoders);
        $this->client = static::createClient();
        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
        $this->container = static::getContainer();

        $this->entityConstants = new EntityMock();
    }

    /**
     * PHPUnit return Warning in case of no test found, so I've created simple test
     * method for check if the protected field are initialized
     */
    public function testInit(): void
    {
        $this->assertNotNull($this->serializer);
        $this->assertNotNull($this->client);
        $this->assertNotNull($this->entityConstants);
        $this->assertNotNull($this->container);
    }

    protected function requestPost(string $content): void
    {
        $this->client->request(IHttpMethod::POST, $this->uri, [], [], [], $content);
        $this->assertResponseIsSuccessful();
    }

    protected function requestPut(int $id, string $content): void
    {
        $this->client->request(IHttpMethod::PUT, $this->uri . "/$id", [], [], [], $content);
        $this->assertResponseIsSuccessful();
    }

    protected function requestGetById(int $id): void
    {
        $this->client->request(IHttpMethod::GET, $this->uri . "/$id");
        $this->assertResponseIsSuccessful();
    }

    protected function requestDelete(int $id): void
    {
        $this->client->request(IHttpMethod::DELETE, $this->uri . "/$id");
        $response = $this->client->getResponse();
        $this->assertNotNull($response);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

}