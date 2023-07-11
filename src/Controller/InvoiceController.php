<?php

namespace App\Controller;

use App\Config\JsonSerializer;
use App\Model\Dto\InvoiceDto;
use App\Service\InvoiceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{

    /**
     * @param InvoiceService $invoiceService
     * @param JsonSerializer $serializer
     */
    public function __construct(
        private readonly InvoiceService $invoiceService,
        private readonly JsonSerializer $serializer
    )
    {
    }


    #[Route('', name: 'app_invoice_get', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/InvoiceController.php',
        ]);
    }

    //TODO consider of using #[MapRequestPayload] instead of $request->getContent() and manual deserialize
    // public function pdfInvoice(#[MapRequestPayload] InvoiceDto $invoiceDto): Response
    #[Route('', name: 'app_invoice_post', methods: 'POST')]
    public function pdfInvoice(Request $request): Response
    {
        $jsonContent = $request->getContent();

        $invoiceDto = $this->serializer->getJsonReflectionExtractorDeserializer()
            ->deserialize($jsonContent, InvoiceDto::class, 'json');

        $this->invoiceService->saveEntity($invoiceDto);

        return $this->json([
            "Status" => "OK"
        ]);
    }

}
