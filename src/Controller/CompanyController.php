<?php

namespace App\Controller;

use App\Model\Dto\CompanyDto;
use App\Model\LimitResult;
use App\Service\CompanyService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/company')]
final class CompanyController extends AbstractCrudController
{
    public function __construct(
        private readonly CompanyService    $companyService,
        protected readonly LoggerInterface $loggerInterface
    )
    {
        parent::__construct($loggerInterface);
    }

    #[Route('', name: 'api_company_fetch_all', methods: IHttpMethod::GET)]
    public function fetchAll(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_0)] int $firstItem = 0,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_1)] int $maxItem = 10
    ): JsonResponse
    {
        $companies = $this->companyService->getByLimitResult(LimitResult::of($firstItem, $maxItem));

        return $this->json($companies);
    }

    #[Route(self::PARAMETER_ID,
        name: 'company_controller_fetch_by_id',
        requirements: self::POSITIVE_INTEGER,
        methods: IHttpMethod::GET)]
    public function fetchById(int $id): JsonResponse
    {
        try {
            $response = $this->companyService->getOneDto($id);
            return $this->json($response);
        } catch (Throwable $throwable) {
            return $this->handleErrorExceptions($throwable);
        }
    }

    #[Route(self::PARAMETER_ID,
        name: 'company_controller_edit_by_id',
        requirements: self::POSITIVE_INTEGER,
        methods: IHttpMethod::PUT)]
    public function editItem(int $id, #[MapRequestPayload] CompanyDto $dto): JsonResponse
    {
        try {
            $response = $this->companyService->editEntity($dto, $id);
            return $this->json($response);
        } catch (Throwable $throwable) {
            return $this->handleErrorExceptions($throwable);
        }
    }

    #[Route('', name: 'company_controller_new_item', methods: IHttpMethod::POST)]
    public function newItem(#[MapRequestPayload] CompanyDto $companyDto): JsonResponse
    {
        try {
            $savedCompany = $this->companyService->saveEntity($companyDto);
            return $this->json($savedCompany, Response::HTTP_CREATED);
        } catch (Throwable $throwable) {
            return $this->handleErrorExceptions($throwable);
        }
    }

    #[Route(self::PARAMETER_ID, name: 'company_controller_delete_item', methods: IHttpMethod::DELETE)]
    public function deleteItem(int $id): JsonResponse
    {
        $this->companyService->deleteEntity($id);
        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
