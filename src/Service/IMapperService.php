<?php

namespace App\Service;

/**
 * @template E of object Entity data model
 * @template D of object DTO data model
 */
interface IMapperService
{
    /**
     * @psalm-param D $dto
     * @psalm-return E
     */
    public function toEntity($dto);

    /**
     * @psalm-param E $entity
     * @psalm-return D
     */
    public function toDto($entity);
}
