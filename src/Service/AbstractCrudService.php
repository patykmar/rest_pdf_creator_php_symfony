<?php

namespace App\Service;

use App\Entity\IEntity;
use App\Exceptions\NotFoundException;
use App\Mapper\ICrudMapper;
use App\Model\LimitResult;
use App\Repository\ICrudRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @template E of object Entity data model
 * @template D of object DTO data model
 * @template M of object mapper
 * @template R of object repository
 * @implements ICrudService<E, D>
 */
abstract class AbstractCrudService implements ICrudService
{
    protected ?IEntity $entity;

    public function __construct(
        protected ICrudMapper $mapper,
        protected ICrudRepository $repository,
        protected string $notFoundErrorMessage
    )
    {
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
        return $this->mapper->toDto($this->getOneEntity($id));
    }

    public function deleteEntity(int $id): void
    {
        $this->checkId($id);
        $this->repository->remove($this->entity, true);
    }

    /**
     * @psalm-param LimitResult $limitResult
     * @psalm-return Collection<D>
     */
    public function getByLimitResult(LimitResult $limitResult): ArrayCollection
    {
        $companiesArray = $this->repository->findByLimitResult($limitResult);
        return $this->mapper->toDtoCollection($companiesArray);
    }

    /**
     * @param int $id
     * @throws NotFoundException
     */
    protected function checkId(int $id): void
    {
        $this->entity = $this->repository->find($id);
        if (is_null($this->entity)) {
            throw new NotFoundException(sprintf($this->notFoundErrorMessage, $id));
        }
    }
}
