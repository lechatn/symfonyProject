<?php

namespace App\Repository;

use App\Entity\HabitsTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HabitsTracking>
 *
 * @method HabitsTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method HabitsTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method HabitsTracking[]    findAll()
 * @method HabitsTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitsTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitsTracking::class);
    }

//    /**
//     * @return HabitsTracking[] Returns an array of HabitsTracking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HabitsTracking
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
