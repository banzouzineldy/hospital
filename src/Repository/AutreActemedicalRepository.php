<?php

namespace App\Repository;

use App\Entity\AutreActemedical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AutreActemedical>
 *
 * @method AutreActemedical|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutreActemedical|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutreActemedical[]    findAll()
 * @method AutreActemedical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutreActemedicalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutreActemedical::class);
    }

    public function save(AutreActemedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AutreActemedical $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return AutreActemedical[] Returns an array of AutreActemedical objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AutreActemedical
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
