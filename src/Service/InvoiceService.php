<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Exceptions\InvalidArgumentException;
use App\Mapper\InvoiceItemMapper;
use App\Mapper\InvoiceMapper;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\InvoiceDto;
use App\Repository\InvoiceRepository;
use AutoMapperPlus\Exception\UnregisteredMappingException;

/**
 * @extends AbstractCrudService<Invoice, InvoiceDataDto, InvoiceMapper, InvoiceRepository>
 *
 * @method Invoice        getOneEntity(int $id)
 * @method InvoiceDataDto getOneDto(int $id)
 */
class InvoiceService extends AbstractCrudService
{
    const NOT_FOUND_ERROR_MSG = "Invoice with id: %d not found";

    public function __construct(
        private readonly InvoiceMapper     $invoiceMapper,
        private readonly InvoiceItemMapper $invoiceItemMapper,
        private readonly CompanyService    $companyService,
        private readonly InvoiceRepository $invoiceRepository,
    )
    {
        parent::__construct($this->invoiceMapper, $this->invoiceRepository, self::NOT_FOUND_ERROR_MSG);
    }

    /**
     * @param InvoiceDto $dto
     * @throws InvalidArgumentException
     * @throws UnregisteredMappingException
     */
    public function saveEntity($dto): InvoiceDataDto
    {
        if ($dto instanceof InvoiceDto) {
            $supplier = $this->companyService->getOneEntity($dto->getSupplierId());
            $subscriber = $this->companyService->getOneEntity($dto->getSubscriberId());

            $newInvoice = $this->invoiceMapper->toEntity($dto);
            $newInvoice
                ->setSupplier($supplier)
                ->setSubscriber($subscriber);

            $this->invoiceItemMapper->addRelationWithInvoice($newInvoice->getInvoiceItems(), $newInvoice);
            $this->invoiceRepository->save($newInvoice);
            $lastInvoice = $this->repository->findLastEntity();
            return $this->invoiceMapper->toDto($lastInvoice);
        }
        throw new InvalidArgumentException("Parameter \$dto for new invoice is not valid type");
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function saveEntityStrict(Invoice $invoice): InvoiceDataDto
    {
        $this->repository->save($invoice);
        return $this->invoiceMapper->toDto($this->repository->findLastEntity());
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function mapDtoToDataDto(InvoiceDto $invoiceDto): InvoiceDataDto
    {
        $supplier = $this->companyService->getOneDto($invoiceDto->getSupplierId());
        $subscriber = $this->companyService->getOneDto($invoiceDto->getSubscriberId());
        return $this->invoiceMapper->dtoToDataDto($invoiceDto, $supplier, $subscriber);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function mapDtoToEntity(InvoiceDto $invoiceDto): Invoice
    {
        $invoiceDataDto = $this->mapDtoToDataDto($invoiceDto);
        return $this->invoiceMapper->toEntity($invoiceDataDto);
    }

    /**
     * @param InvoiceDto $dto
     * @param int $id
     * @return InvoiceDataDto
     * @throws UnregisteredMappingException
     */
    public function editEntity($dto, int $id): InvoiceDataDto
    {
        // cannot use repository:save method. Refactor edit method.
        $this->checkId($id);
        $companies = $this->companyService->findTwoCompaniesByIds($dto->getSupplierId(), $dto->getSubscriberId());

        $invoiceFromController = $this->invoiceMapper->toEntity($dto);
        $invoiceFromController
            ->setSupplier($companies[$dto->getSupplierId()])
            ->setSubscriber($companies[$dto->getSubscriberId()]);

        $updatedEntity = $this->mapper->mappingBeforeEditEntity($this->entity, $invoiceFromController);
        $this->repository->save($updatedEntity);
        return $this->getOneDto($id);
    }


}
