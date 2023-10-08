<?php

namespace App\Tests\Controller\trait;

use Symfony\Component\HttpFoundation\Response;

trait JsonTestUtilsTrait
{
    protected function jsonSerialize(object $object): string
    {
        return $this->serializer->serialize($object, self::JSON);
    }

    protected function jsonDeserialize(string $className, string $jsonContent): object|array
    {
        return $this->serializer->deserialize($jsonContent, $className, self::JSON);
    }

    /**
     * Method validate response from client via PHPUnit assertion and deserialize content from response
     * @param string $className destination type of deserialization
     * @param Response $response client response which will be validated and deserialized
     * @return object|array of $className
     */
    protected function jsonValidateAndDeserialize(string $className, Response $response): object|array
    {
        $this->assertNotNull($response);
        $jsonContent = $response->getContent();
        $this->assertJson($jsonContent);
        return $this->jsonDeserialize($className, $jsonContent);
    }
}
