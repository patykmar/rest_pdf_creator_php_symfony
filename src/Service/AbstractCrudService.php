<?php

namespace App\Service;

use App\Entity\EntityInterface;
use App\Exceptions\NotFoundException;
use App\Exceptions\NotImplementException;
use App\Model\LimitResult;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template E of object Entity data model
 * @template D of object DTO data model
 * @template M of object mapper
 * @template R of object repository
 * @implements ICrudService<E, D>
 * @implements IMapperService<E, D>
 */
class AbstractCrudService implements ICrudService, IMapperService
{
    protected ?EntityInterface $entity;
    /**
     * @psalm-var M $mapper
     */
    private object $mapper;

    /**
     * @psalm-var R $repository
     */
    private object $repository;

    /**
     * @psalm-param M $mapper
     * @psalm-param R $repository
     */
    public function __construct(object $mapper, object $repository)
    {
        $this->mapper = $mapper;
        $this->repository = $repository;
    }

    /**
     * @psalm-return E
     */
    public function getOneEntity(int $id)
    {
        $this->checkId($id);
        return $this->entity;
    }

    public function getOneDto(int $id)
    {
        throw new NotImplementException("Method getOneDto is not implement");
    }

    public function saveEntity($dto)
    {
        throw new NotImplementException("Method saveEntity is not implement");
    }

    public function editEntity($dto, int $id)
    {
        throw new NotImplementException("Method editEntity is not implement");
    }

    public function deleteEntity(int $id): void
    {
        $this->checkId($id);
        $this->repository->remove($this->entity, true);
    }

    public function toEntity($dto)
    {
        throw new NotImplementException("Method toEntity is not implement");
    }

    public function toDto($entity)
    {
        throw new NotImplementException("Method toDto is not implement");
    }

    public function getByLimitResult(LimitResult $limitResult): ArrayCollection
    {
        $companiesArray = $this->repository->findByLimitResult($limitResult);
        return $this->mapper->toDtoCollection($companiesArray);
    }

    /**
     * @throws NotFoundException
     */
    protected function checkId(int $id): void
    {
        $this->entity = $this->repository->find($id);
        if (is_null($this->entity)) {
            throw new NotFoundException(sprintf("Company with id: %d not found", $id));
        }
    }
}
