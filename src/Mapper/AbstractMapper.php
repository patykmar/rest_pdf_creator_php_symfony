<?php

namespace App\Mapper;

use App\Exceptions\InvalidArgumentException;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

abstract class AbstractMapper implements ICrudMapper
{
    /**
     * @param string $dtoType
     * @param string $entityType
     * @param AutoMapperInterface $mapper
     */
    public function __construct(
        protected readonly string              $dtoType,
        protected readonly string              $entityType,
        protected readonly AutoMapperInterface $mapper
    )
    {
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function toEntity($dto)
    {
        if ($dto instanceof $this->dtoType) {
            return $this->mapper->map($dto, $this->entityType);
        }
        throw new InvalidArgumentException("Input parameter type is unexpected");
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function toDto($entity)
    {
        if ($entity instanceof $this->entityType) {
            return $this->mapper->map($entity, $this->dtoType);
        }
        throw new InvalidArgumentException("Input parameter type is unexpected");
    }

    public function toDtoCollection(Collection $entities): ArrayCollection
    {
        return new ArrayCollection($this->mapper->mapMultiple($entities, $this->dtoType));
    }
}
