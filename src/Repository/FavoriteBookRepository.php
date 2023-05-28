<?php

namespace App\Repository;

use App\Entity\FavoriteBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteBook[]    findAll()
 * @method FavoriteBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteBook::class);
    }

    // You can add custom methods to this repository as needed
}
