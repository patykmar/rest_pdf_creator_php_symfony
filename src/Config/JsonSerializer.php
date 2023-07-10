<?php

namespace App\Config;

use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializer
{

    public function getJsonSerializer(): Serializer
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    public function getJsonReflectionExtractorDeserializer(): Serializer
    {
        $objectNormalizer = new ObjectNormalizer(
            null,
            null,
            null,
            new ReflectionExtractor()
        );

        return new Serializer([$objectNormalizer, new ArrayDenormalizer()], [new JsonEncoder()]);
    }

}
