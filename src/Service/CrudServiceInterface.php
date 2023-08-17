<?php

namespace App\Service;

use App\Exceptions\NotFoundException;

/**
 * @template E of object Entity data model
 * @template D of object DTO data model
 */
interface CrudServiceInterface
{
    /**
     * @param int $id
     * @psalm-return E
     */
    public function getOneEntity(int $id);

    /**
     * @param int $id
     * @psalm-return D
     */
    public function getOneDto(int $id);

    /**
     * @param D $dto
     * @psalm-return D
     */
    public function saveEntity($dto);

    /**
     * @param D $dto
     * @param int $id
     * @psalm-return D
     */
    public function editEntity($dto, int $id);

    /**
     * @param int $id
     * @throws NotFoundException
     */
    public function deleteEntity(int $id): void;
}
