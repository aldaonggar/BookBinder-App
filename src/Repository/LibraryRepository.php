<?php

namespace App\Repository;

use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Library>
 *
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    public function save(Library $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Library $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAlphabeticly()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }

    public function get21Libraries(int $page): array{
        $query = $this->createQueryBuilder('l')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page-1)*21)
            ->setMaxResults(21);

        return $paginator->getIterator()->getArrayCopy();
    }

    public function getNumberOfLibraries(): int{
        $qb = $this->createQueryBuilder('l');
        $qb->select('COUNT(l)');
        $query = $qb->getQuery();
        return (int) $query->getSingleScalarResult();
    }

    public function searchLibrariesByNameAndCity($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('library');
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('library.name', ':searchTerm'),
                    $queryBuilder->expr()->like('library.city', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->addOrderBy(
                'CASE
                    WHEN library.name LIKE :searchTerm THEN 1
                    WHEN library.city LIKE :searchTerm THEN 2
                    ELSE 3
                END'
            );

        return $queryBuilder->getQuery()->getResult();

    }


//    /**
//     * @return Library[] Returns an array of Library objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Library
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
