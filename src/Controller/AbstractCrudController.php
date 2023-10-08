<?php

namespace App\Controller;

use App\Exceptions\NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

abstract class AbstractCrudController extends AbstractController implements ICrudController
{
    protected LoggerInterface $logger;

    protected function handleErrorExceptions(Throwable $throwable): JsonResponse
    {
        $this->logger->error($throwable->getMessage());
        //TODO: most probably is better solution for error handling by Symfony. Try refactor it later
        if ($throwable instanceof NotFoundException) {
            return $this->json([
                'error_code' => '404',
                'error_message' => $throwable->getMessage()
            ]);
        }

        return $this->json([
            'error_code' => '500',
            'error_message' => $throwable->getMessage()
        ]);
    }

}
