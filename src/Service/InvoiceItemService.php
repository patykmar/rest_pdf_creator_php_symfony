<?php

namespace App\Service;

use App\Entity\InvoiceItem;
use App\Mapper\SomeMapper;
use App\Mapper\InvoiceMapper;
use App\Model\Dto\InvoiceItemDto;
use App\Model\LimitResult;
use App\Repository\InvoiceItemRepository;
use AutoMapperPlus\Exception\UnregisteredMappingException;
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
    public function __construct(
        protected readonly InvoiceItemRepository $repository,
        protected readonly SomeMapper $mapper
    )
    {
        parent::__construct($this->mapper, $this->repository);
    }

    /**
     * @param InvoiceItemDto $dto
     * @return InvoiceItemDto
     * @throws UnregisteredMappingException
     */
    public function saveEntity($dto): InvoiceItemDto
    {
        $this->repository->save($this->mapper->toEntity($dto));
        $entity = $this->repository->findLastEntity();
        return $this->mapper->toDto($entity);
    }

    public function editEntity($dto, int $id)
    {
        // TODO: Implement editEntity() method.
    }
}
