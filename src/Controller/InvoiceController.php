<?php

namespace App\Controller;

use App\Model\Dto\InvoiceDto;
use App\Model\LimitResult;
use App\Service\InvoiceService;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * @implements AbstractCrudController
 */
#[Route('/invoice')]
class InvoiceController extends AbstractCrudController
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
        protected LoggerInterface       $logger
    )
    {
        parent::__construct($logger);
    }

    #[Route('', name: 'invoice_controller_new_item', methods: self::POST)]
    public function newItem(#[MapRequestPayload] InvoiceDto $newItem): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->saveEntity($newItem);
            return $this->json($invoice, Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    #[Route('', name: 'invoice_controller_fetch_all', methods: IHttpMethod::GET)]
    public function fetchAll(
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_0)] int $firstItem = 0,
        #[MapQueryParameter(filter: FILTER_VALIDATE_INT, options: self::OPTIONS_MIN_RANGE_1)] int $maxItem = 10
    ): JsonResponse
    {
        try {
            $invoice = $this->invoiceService->getByLimitResult(LimitResult::of($firstItem, $maxItem));
            return $this->json($invoice);
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    #[Route(self::PARAMETER_ID,
        name: 'invoice_controller_fetch_by_id',
        requirements: self::POSITIVE_INTEGER,
        methods: self::GET
    )]
    public function fetchById(int $id): JsonResponse
    {
        try {
            return $this->json($this->invoiceService->getOneDto($id));
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    #[Route(self::PARAMETER_ID,
        name: 'invoice_controller_edit_by_id',
        requirements: self::POSITIVE_INTEGER,
        methods: self::PUT)]
    public function editItem(int $id, #[MapRequestPayload] InvoiceDto $dto): JsonResponse
    {
        try {
            $editedInvoice = $this->invoiceService->editEntity($dto, $id);
            return $this->json($editedInvoice);
        } catch (Throwable $e) {
            return $this->handleErrorExceptions($e);
        }
    }

    public function deleteItem(int $id): JsonResponse
    {
        return $this->handleNotImplementMethod();
    }


}
