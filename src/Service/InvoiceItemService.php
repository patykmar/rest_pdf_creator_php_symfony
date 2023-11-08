<?php

namespace App\Service;

use App\Entity\InvoiceItem;
use App\Exceptions\NotImplementException;
use App\Mapper\InvoiceItemMapper;
use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceItemDto;
use App\Model\LimitResult;
use App\Repository\InvoiceItemRepository;
use Doctrine\Common\Collections\ArrayCollection;

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
        protected readonly InvoiceItemRepository $invoiceItemRepository
    )
    {
        parent::__construct(
            $this->invoiceItemMapper,
            $this->invoiceItemRepository,
            self::NOT_FOUND_ERROR_MSG
        );
    }

    /**
     * @param InvoiceItemDto $dto
     * @return InvoiceItemDto
     */
    public function saveEntity($dto): InvoiceItemDto
    {
        $this->repository->save($this->mapper->toEntity($dto));
        $entity = $this->repository->findLastEntity();
        return $this->mapper->toDto($entity);
    }

    public function editEntity($dto, int $id)
    {
        throw new NotImplementException("Method editEntity is not implemented yet");
    }
}
