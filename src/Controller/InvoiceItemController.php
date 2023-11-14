<?php

namespace App\Controller;

use App\Model\Dto\InvoiceItemDto;
use App\Service\InvoiceItemService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route("/invoice-item")]
class InvoiceItemController extends AbstractCrudController
{
    public function __construct(
        private readonly InvoiceItemService $service,
        protected readonly LoggerInterface  $iLogger
    )
    {
        parent::__construct($this->iLogger);
    }

    /**
     * @inheritDoc
     */
    #[Route('', name: 'invoice_item_controller_fetch_all', methods: self::GET)]
    public function fetchAll(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_0)] int $firstItem = 0,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_1)] int $maxItem = 10
    ): JsonResponse
    {
        return $this->json('', Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * Retrieve all invoice items by invoice ID
     * @param int $id invoice ID,
     */
    #[Route('/invoice/{id}')]
    public function fetchAllByInvoiceId(int $id): JsonResponse
    {
        try {
            $invoiceItems = $this->service->retrieveInvoiceItemsByInvoice($id);
            return $this->json($invoiceItems);
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    #[Route(
        "/{invoiceId}",
        name: 'invoice_item_controller_new_item',
        requirements: self::POSITIVE_INTEGER,
        methods: IHttpMethod::POST
    )]
    public function newItem(int $invoiceId, #[MapRequestPayload] InvoiceItemDto $invoiceItemDto): JsonResponse
    {
        //TODO
        return $this->json("Method newItem is not implemented", Response::HTTP_NOT_IMPLEMENTED);
//        try {
//            $savedCompany = $this->companyService->saveEntity($companyDto);
//            return $this->json($savedCompany, Response::HTTP_CREATED);
//        } catch (Throwable $throwable) {
//            return $this->handleErrorExceptions($throwable);
//        }
    }

    #[Route(self::PARAMETER_ID,
        name: 'invoice_item_controller_fetch_by_id',
        requirements: self::POSITIVE_INTEGER,
        methods: self::GET)]
    public function fetchById(int $id): JsonResponse
    {
        try {
            return $this->json($this->service->getOneDto($id));
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    public function deleteItem(int $id): JsonResponse
    {
        try {
            $this->service->deleteEntity($id);
            return $this->json('', Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }
}
