<?php

namespace App\Repository;

use App\Entity\InvoiceItem;
use App\Model\LimitResult;
use App\Trait\CrudRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @extends ServiceEntityRepository<InvoiceItem>
 *
 * @method InvoiceItem|null        find($id, $lockMode = null, $lockVersion = null)
 * @method InvoiceItem|null        findOneBy(array $criteria, array $orderBy = null)
 * @method InvoiceItem[]           findAll()
 * @method InvoiceItem[]           findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method InvoiceItem|null        findLastEntity()
 * @method Collection<InvoiceItem> findByLimitResult(LimitResult $limitResult)
 */
class InvoiceItemRepository extends ServiceEntityRepository implements ICrudRepository
{
    use CrudRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceItem::class);
    }

//    /**
//     * @return InvoiceItemEntity[] Returns an array of InvoiceItemEntity objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InvoiceItemEntity
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
