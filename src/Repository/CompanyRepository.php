<?php

namespace App\Repository;

use App\Entity\Company;
use App\Trait\CrudRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Company>
 * @uses CrudRepositoryTrait<Company>
 * @implements ICrudRepository<Company>
 *
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Company|null findLastEntity()
 */
class CompanyRepository extends ServiceEntityRepository implements ICrudRepository
{
    use CrudRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    /**
     * @param int $id1
     * @param int $id2
     * @return array|null Company[]
     */
    public function findTwoCompanies(int $id1, int $id2): array|null
    {
        $em = $this->getEntityManager();
        $dql = "SELECT c FROM App\Entity\Company c WHERE c.id in (:id1, :id2)";

        $query = $em->createQuery($dql)
            ->setParameter('id1', $id1)
            ->setParameter('id2', $id2);

        return $query->getResult();

    }

//    /**
//     * @return Company[] Returns an array of Company objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Company
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
