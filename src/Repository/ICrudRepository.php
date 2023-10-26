<?php

namespace App\Repository;


use App\Entity\IEntity;
use App\Model\LimitResult;
use Doctrine\Common\Collections\Collection;

interface ICrudRepository
{
    function findByLimitResult(LimitResult $limitResult): Collection;

    function find(int $id);

    function save(IEntity $entity, bool $flush): void;

    function remove(IEntity $entity, bool $flush = false): void;

    function findLastEntity();
}
