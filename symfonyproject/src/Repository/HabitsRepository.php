<?php

namespace App\Repository;

use App\Entity\Habits;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habits>
 *
 * @method Habits|null find($id, $lockMode = null, $lockVersion = null)
 * @method Habits|null findOneBy(array $criteria, array $orderBy = null)
 * @method Habits[]    findAll()
 * @method Habits[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HabitsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habits::class);
    }

//    /**
//     * @return Habits[] Returns an array of Habits objects
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

//    public function findOneBySomeField($value): ?Habits
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
