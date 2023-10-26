<?php

namespace App\Controller;

use App\Exceptions\NotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

abstract class AbstractCrudController extends AbstractController implements ICrudController
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function handleErrorExceptions(Throwable $throwable): JsonResponse
    {
        $this->logger->error($throwable->getMessage());
        //TODO: most probably is better solution for error handling by Symfony. Try refactor it later
        if ($throwable instanceof NotFoundException) {
            return $this->json([
                'error_code' => '404',
                'error_message' => $throwable->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'error_code' => '500',
            'error_message' => $throwable->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function handleNotImplementMethod(): JsonResponse
    {
        return $this->json([
            'error_code' => '405',
            'error_message' => 'Method is not allowed or not implemented'
        ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

}
