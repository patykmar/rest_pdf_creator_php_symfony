<?php

namespace App\Service;

use App\Exceptions\NotFoundException;
use App\Model\LimitResult;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template E of object Entity data model
 * @template D of object DTO data model
 */
interface ICrudService
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
     * @param LimitResult $limitResult
     * @psalm-return ArrayCollection<D>
     */
    public function getByLimitResult(LimitResult $limitResult): ArrayCollection;

    /**
     * @psalm-param D $dto
     * @psalm-return D
     */
    public function saveEntity($dto);

    /**
     * @psalm-param D $dto
     * @psalm-param int $id
     * @psalm-return D
     */
    public function editEntity($dto, int $id);

    /**
     * @param int $id
     * @throws NotFoundException
     */
    public function deleteEntity(int $id): void;
}
