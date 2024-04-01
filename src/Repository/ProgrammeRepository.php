<?php

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Programme>
 *
 * @method Programme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programme[]    findAll()
 * @method Programme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

    public function findBySearchCriteria($criteria)
{
    $qb = $this->createQueryBuilder('p');

    if (!empty($criteria['titre_pro'])) {
        $qb->andWhere('p.titre_pro LIKE :titre_pro')
            ->setParameter('titre_pro', '%' . $criteria['titre_pro'] . '%');
    }

    if (!empty($criteria['plan_pro'])) {
        $qb->andWhere('p.plan_pro LIKE :plan_pro')
            ->setParameter('plan_pro', '%' . $criteria['plan_pro'] . '%');
    }

    if (!empty($criteria['date_pro'])) {
        $qb->andWhere('p.date_pro = :date_pro')
            ->setParameter('date_pro', $criteria['date_pro']);
    }

    return $qb->getQuery()->getResult();
}


public function findByCategory(int $categoryId): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.categorypro', 'c')
            ->andWhere('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
    }



    public function findByKeyword($keyword)
{
    return $this->createQueryBuilder('p')
        ->where('p.titre_pro LIKE :keyword')
        ->orWhere('p.plan_pro LIKE :keyword')
        ->setParameter('keyword', '%' . $keyword . '%')
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Programme[] Returns an array of Programme objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Programme
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
