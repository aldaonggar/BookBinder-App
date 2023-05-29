<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAlphabeticly()
    {
        return $this->findBy(array(), array('firstname' => 'ASC'));
    }

    public function get21People(int $page): array{
        $query = $this->createQueryBuilder('p')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page-1)*21)
            ->setMaxResults(21);

        return $paginator->getIterator()->getArrayCopy();
    }

    public function getNumberOfPeople(): int{
        $qb = $this->createQueryBuilder('p');
        $qb->select('COUNT(p)');
        $query = $qb->getQuery();
        return (int) $query->getSingleScalarResult();
    }

    public function searchPeopleByName($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('person');
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('person.firstname', ':searchTerm'),
                    $queryBuilder->expr()->like('person.lastname', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->addOrderBy(
                'CASE
                    WHEN person.firstname LIKE :searchTerm THEN 1
                    WHEN person.lastname LIKE :searchTerm THEN 2
                    ELSE 3
                END'
            );

        return $queryBuilder->getQuery()->getResult();

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
