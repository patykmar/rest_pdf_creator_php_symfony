<?php

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * TODO: It is not working as I expected, common error handling will do in {@link AbstractCrudController}
 *
 * I expected this class as common error handling for API call, but I cannot catch error in normalize() method,
 * so I decided to make the error handling in {@link AbstractCrudController}
 */
class MyCustomProblemNormalizer implements NormalizerInterface
{

    /**
     * @psalm-param FlattenException $object
     * @inheritDoc
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
        return [
            'content' => 'This is my custom problem normalizer.',
            'exception' => [
                'message' => $object->getMessage(),
                'code' => $object->getStatusCode(),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof FlattenException;
    }

    public function getSupportedTypes(?string $format): array
    {
        // here you can put breakpoint. For functional test is variable $format set as 'json'
        return [
            FlattenException::class
        ];
    }
}
