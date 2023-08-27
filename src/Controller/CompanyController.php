<?php

namespace App\Controller;

use App\Model\Dto\CompanyDto;
use App\Model\LimitResult;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/company')]
final class CompanyController extends AbstractController implements ICrudController
{
    public function __construct(
        private readonly CompanyService $companyService
    )
    {
    }

    #[Route('', name: 'api_company_fetch_all', methods: IHttpMethod::GET)]
    public function fetchAll(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: ['options' => ['min_range' => 0]])] int $firstItem = 0,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: ['options' => ['min_range' => 1]])] int $maxItem = 10
    ): JsonResponse
    {

        $limitResult = new LimitResult($firstItem, $maxItem);
        $companies = $this->companyService->getByLimitResult($limitResult);

        return $this->json($companies);
    }

    #[Route(self::PARAMETER_ID, name: 'company_controller_fetch_by_id', requirements: ['id' => '\d+'], methods: IHttpMethod::GET)]
    public function fetchById(int $id): JsonResponse
    {
        $response = $this->companyService->getOneDto($id);
        return $this->json($response);
    }

    #[Route(self::PARAMETER_ID, name: 'company_controller_edit_by_id', requirements: ['id' => '\d+'], methods: IHttpMethod::PUT)]
    public function editItem(int $id, #[MapRequestPayload] CompanyDto|IDto $dto): JsonResponse
    {
        return $this->json([
            "Status" => "OK"
        ]);
    }

    #[Route('', name: 'company_controller_new_item', methods: IHttpMethod::POST)]
    public function newItem(#[MapRequestPayload] CompanyDto $companyDto): JsonResponse
    {
        $savedCompany = $this->companyService->saveEntity($companyDto);
        return $this->json($savedCompany, Response::HTTP_CREATED);
    }

    #[Route(self::PARAMETER_ID, name: 'company_controller_delete_item', methods: IHttpMethod::DELETE)]
    public function deleteItem(int $id): JsonResponse
    {
        $this->companyService->deleteEntity($id);
        return $this->json('', Response::HTTP_NO_CONTENT);
    }
}
