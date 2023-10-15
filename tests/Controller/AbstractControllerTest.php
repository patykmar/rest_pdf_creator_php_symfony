<?php

namespace App\Tests\Controller;

use App\Tests\Controller\trait\JsonTestUtilsTrait;
use App\Tests\Controller\trait\MockTrait;
use App\Tests\Mock\Entity\EntityMock;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
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

    public const JSON = "json";
    public const METHOD_NAME_SAVE = 'save';
    public const METHOD_NAME_FIND = 'find';
    public const METHOD_NAME_REMOVE = 'remove';
    public const METHOD_NAME_FIND_ONE_BY = 'findOneBy';

    protected Serializer $serializer;
    protected KernelBrowser $client;
    protected EntityMock $entityConstants;
    protected ContainerInterface $container;

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
    public function testInit()
    {
        $this->assertNotNull($this->serializer);
        $this->assertNotNull($this->client);
        $this->assertNotNull($this->entityConstants);
        $this->assertNotNull($this->container);
    }

}