<?php

namespace App\Mapper;

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
     * @psalm-return Collection<D> collection of DTO data types
     */
    public function toDtoCollection(Collection $entities): Collection;

    /**
     * Method which mapping consumer values to exist entity in DB, due to doctrine can do edit only
     * on object which was loaded from DB unlike spring JPA where is possible save entity with ID
     * and JPA is understood it as edit item.
     * @param E $entityFromDb
     * @param E $entityFromConsumer
     * @return E
     */
    public function mappingBeforeEditEntity($entityFromDb, $entityFromConsumer);
}
