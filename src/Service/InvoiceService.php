<?php

namespace App\Service;

use App\Entity\Invoice;
use App\Exceptions\InvalidArgumentException;
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
    public function __construct(
        private readonly InvoiceMapper     $invoiceMapper,
        private readonly CompanyService    $companyService,
        private readonly InvoiceRepository $repository,
    )
    {
        parent::__construct($this->invoiceMapper, $this->repository);
    }

    /**
     * @param InvoiceDto $dto
     * @throws InvalidArgumentException
     * @throws UnregisteredMappingException
     */
    public function saveEntity($dto): InvoiceDataDto
    {
        if ($dto instanceof InvoiceDto) {
            $invoiceDataDto = $this->mapDtoToDataDto($dto);
            return $this->saveEntityStrict($invoiceDataDto);
        }
        throw new InvalidArgumentException("Parameter \$dto for new invoice is not valid type");
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function saveEntityStrict(InvoiceDataDto $invoiceDataDto): InvoiceDataDto
    {
        $invoice = $this->invoiceMapper->toEntity($invoiceDataDto);
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
        $invoiceDataDto = $this->mapDtoToDataDto($dto);
        $invoiceDataDto->setId($id);

        return $this->saveEntityStrict($invoiceDataDto);
    }


}
