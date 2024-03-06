<?php

namespace App\Repository;

use App\Entity\CommentProg;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentProg>
 *
 * @method CommentProg|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentProg|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentProg[]    findAll()
 * @method CommentProg[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentProgRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentProg::class);
    }

//    /**
//     * @return CommentProg[] Returns an array of CommentProg objects
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

//    public function findOneBySomeField($value): ?CommentProg
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
