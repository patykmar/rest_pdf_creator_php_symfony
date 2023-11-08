<?php

namespace App\Trait;

use App\Entity\IEntity;
use App\Model\LimitResult;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @template E entity object
 */
trait CrudRepositoryTrait
{
    /**
     * @psalm-return Collection<E> returns an array of Company objects
     */
    public function findByLimitResult(LimitResult $limitResult): Collection
    {
        return new ArrayCollection($this->createQueryBuilder('c')
            ->setFirstResult($limitResult->getFirst())
            ->setMaxResults($limitResult->getMax())
            ->getQuery()
            ->getResult());
    }

    public function save(IEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(IEntity $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @psalm-return E|null
     */
    public function findLastEntity()
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }
}
