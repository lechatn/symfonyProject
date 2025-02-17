<?php

namespace App\Repository;

use App\Entity\HabitTracking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HabitTracking>
 *
 * @method HabitTracking|null find($id, $lockMode = null, $lockVersion = null)
 * @method HabitTracking|null findOneBy(array $criteria, array $orderBy = null)
 * @method HabitTracking[]    findAll()
 * @method HabitTracking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitTrackingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HabitTracking::class);
    }

//    /**
//     * @return HabitTracking[] Returns an array of HabitTracking objects
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

//    public function findOneBySomeField($value): ?HabitTracking
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
