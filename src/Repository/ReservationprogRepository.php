<?php

namespace App\Repository;

use App\Entity\Reservationprog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservationprog>
 *
 * @method Reservationprog|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservationprog|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservationprog[]    findAll()
 * @method Reservationprog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationprogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservationprog::class);
    }

//    /**
//     * @return Reservationprog[] Returns an array of Reservationprog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reservationprog
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
