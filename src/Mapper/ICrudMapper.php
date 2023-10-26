<?php

namespace App\Mapper;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


/**
 * @template E entity data type
 * @template D dto data type
 */
interface ICrudMapper
{
    /**
     * @psalm-param D $dto data type for mapping
     * @psalm-return E entity data type after mapping
     */
    public function toEntity($dto);

    /**
     * @psalm-param E $entity data type, which will be mapped
     * @psalm-return D DTO data type after mapping
     */
    public function toDto($entity);

    /**
     * @psalm-var Collection<E> $entities collection of entity data type
     * @psalm-return ArrayCollection<D> collection of DTO data types
     */
    public function toDtoCollection(Collection $entities): ArrayCollection;
}
