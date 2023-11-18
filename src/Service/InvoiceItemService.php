<?php

namespace App\Service;

use App\Entity\InvoiceItem;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotImplementException;
use App\Mapper\InvoiceItemMapper;
use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceItemDto;
use App\Model\LimitResult;
use App\Repository\InvoiceItemRepository;
use App\Repository\InvoiceRepository;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @extends AbstractCrudService<InvoiceItem, InvoiceItemDto, InvoiceMapper, InvoiceItemRepository>
 * @method InvoiceItem getOneEntity(int $id)
 * @method InvoiceItemDto getOneDto(int $id)
 * @method void deleteEntity(int $id)
 * @method ArrayCollection<InvoiceItemDto> getByLimitResult(LimitResult $limitResult)
 */
class InvoiceItemService extends AbstractCrudService
{
    const NOT_FOUND_ERROR_MSG = "Invoice item with id: %d not found";

    public function __construct(
        protected readonly InvoiceItemMapper     $invoiceItemMapper,
        protected readonly InvoiceItemRepository $invoiceItemRepository,
        protected readonly InvoiceRepository     $invoiceRepository
    )
    {
        parent::__construct(
            $this->invoiceItemMapper,
            $this->invoiceItemRepository,
            self::NOT_FOUND_ERROR_MSG
        );
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function newInvoiceItem(int $invoiceId, InvoiceItemDto $invoiceItemDto): InvoiceItemDto
    {
        $invoice = $this->invoiceRepository->find($invoiceId);
        if (is_null($invoice)) {
            throw new NotFoundException("Invoice with ID: $invoiceId is not found");
        }

        $invoiceItemEntity = $this->invoiceItemMapper->toEntity($invoiceItemDto)
            ->setInvoice($invoice);

        $this->invoiceItemRepository->save($invoiceItemEntity);
        $lastSaveEntity = $this->invoiceItemRepository->findLastEntity();
        return $this->invoiceItemMapper->toDto($lastSaveEntity);
    }

    /**
     * @param InvoiceItemDto $dto
     * @return InvoiceItemDto
     */
    public function saveEntity($dto): InvoiceItemDto
    {
        throw new MethodNotAllowedException("Method save entity is not allowed, use newInvoiceItem() instead of");
    }

    public function editEntity($dto, int $id)
    {
        throw new NotImplementException("Method editEntity is not implemented yet");
    }

    public function retrieveInvoiceItemsByInvoice(int $id): Collection
    {
        $invoice = $this->invoiceRepository->find($id);
        if (is_null($invoice)) {
            throw new NotFoundException("Invoice with ID: $id is not found");
        }

        $invoiceItemsEntity = $this->invoiceItemRepository->findBy(['invoice' => $id]);
        return $this->invoiceItemMapper->toDtoCollection(new ArrayCollection($invoiceItemsEntity));
    }
}
