<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
* @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findAllUsersWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender' , 'u.isBanned')
            ->where('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllFemalesWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
            ->andWhere('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere('u.gender = :gender')
            ->setParameter('gender', 'female')
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllMalesWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
            ->andWhere('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere('u.gender = :gender')
            ->setParameter('gender', 'male')
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllBannedWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
            ->andWhere('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere('u.isBanned = :banned')
            ->setParameter('banned', '1')
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllAdminsWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
            ->andWhere('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere("u.roles LIKE :role")
            ->setParameter('role', '%"ROLE_ADMIN"%')
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllMembersWithSpecificFieldsExceptCurrentUser(User $currentUser)
    {
        return $this->createQueryBuilder('u')
            ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
            ->andWhere('u != :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere("u.roles LIKE :role")
            ->setParameter('role', '%"ROLE_USER"%')
            ->orderBy('u.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    public function countFemales()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.gender)')
            ->Where('u.gender = :female')
            ->setParameter('female', 'female')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countMales()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.gender)')
            ->Where('u.gender = :male')
            ->setParameter('male', 'male')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countBannedUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.isBanned)')
            ->Where('u.isBanned = :ban')
            ->setParameter('ban', '1')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countNoBannedUsers()
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.isBanned)')
            ->Where('u.isBanned = :ban')
            ->setParameter('ban', '0')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getUserEvolutionData(): array
    {
        return $this->createQueryBuilder('u')
            ->select("SUBSTRING(u.created_at, 1, 10) AS date, COUNT(u.cin) AS userCount")
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function showUsersSortedByLastname(User $currentUser)
    {
        return $this->createQueryBuilder('u')
        ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender' , 'u.isBanned')
        ->where('u != :currentUser')
        ->setParameter('currentUser', $currentUser)
        ->orderBy('u.lastname', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function showUsersSortedByFirstname(User $currentUser)
    {
        return $this->createQueryBuilder('u')
        ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender' , 'u.isBanned')
        ->where('u != :currentUser')
        ->setParameter('currentUser', $currentUser)
        ->orderBy('u.firstname', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function searchByCin(string $cin)
    {
        return $this->createQueryBuilder('u')
        ->select('u.cin', 'u.email', 'u.roles', 'u.lastname', 'u.firstname', 'u.gender', 'u.isBanned')
        ->where('u.cin like :cin')
        ->setParameter('cin', $cin)
        ->getQuery()
        ->getResult();
    }

    public function getRolesDistribution(): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u.roles');
        $roles = $qb->getQuery()->getResult();

        $rolesDistribution = [];

        foreach ($roles as $user) {
            $userRoles = $user['roles'];

            foreach ($userRoles as $role) {
                if ($role !== null && $role !== '') {
                    if (isset($rolesDistribution[$role])) {
                        $rolesDistribution[$role]++;
                    } else {
                        $rolesDistribution[$role] = 1;
                    }
                }
            }
        }

        return $rolesDistribution;
    }
    


//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
