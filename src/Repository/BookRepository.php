<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function save(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Book $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function get21Books(int $page): array
    {
        if ($page < 1){
            return [];
        }
        $query = $this->createQueryBuilder('b')
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->getQuery()
            ->setFirstResult(($page-1)*21)
            ->setMaxResults(21);

        return $paginator->getIterator()->getArrayCopy();
    }

    public function getTop3Books(): array
    {
        $query = $this->createQueryBuilder('b')
            ->setMaxResults(3)
            ->getQuery();
        return $query->execute();
    }

    public function getNumberOfBooks(): int
    {
        $qb = $this->createQueryBuilder('b');
        $qb->select('COUNT(b)');
        $query = $qb->getQuery();
        return (int) $query->getSingleScalarResult();
    }

    public function searchByTitle($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->where($queryBuilder->expr()->like('book.title', ':searchTerm'))
            ->setParameter('searchTerm', '%' . $searchTerm . '%');

        return $queryBuilder->getQuery()->getResult();
    }

    public function searchBooksByAuthorAndTitle($searchTerm)
    {
        $queryBuilder = $this->createQueryBuilder('book');
        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->like('book.title', ':searchTerm'),
                    $queryBuilder->expr()->like('book.author', ':searchTerm'),
                    $queryBuilder->expr()->eq('book.isbn', ':searchTerm')
                )
            )
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->addOrderBy(
                'CASE
                    WHEN book.title LIKE :searchTerm THEN 1
                    WHEN book.author LIKE :searchTerm THEN 2
                    WHEN book.isbn = :searchTerm THEN 3
                    ELSE 4
                END'
            );

        return $queryBuilder->getQuery()->getResult();

    }
    public function getBooksForPage(int $page): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('20*(:page-1)+1 < b.id < 20*:page')
            ->setParameter('page', $page)
        ;

        $query = $qb->getQuery();

        return $query->execute();
    }


//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
