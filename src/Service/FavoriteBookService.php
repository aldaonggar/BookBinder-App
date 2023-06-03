<?php

namespace App\Service;

use App\Entity\Book;
use App\Entity\FavoriteBook;
use App\Entity\User;
use App\Repository\FavoriteBookRepository;
use Doctrine\ORM\EntityManagerInterface;

class FavoriteBookService
{
    private $entityManager;
    private $favoriteBookRepository;

    public function __construct(EntityManagerInterface $entityManager, FavoriteBookRepository $favoriteBookRepository)
    {
        $this->entityManager = $entityManager;
        $this->favoriteBookRepository = $favoriteBookRepository;
    }

    public function toggleFavorite(User $user, Book $book): bool
    {
        $favoriteBook = $this->favoriteBookRepository->findOneBy([
            'user' => $user,
            'book' => $book,
        ]);

        if ($favoriteBook) {
            $this->entityManager->remove($favoriteBook);
            $this->entityManager->flush();

            return false;
        } else {
            $favoriteBook = new FavoriteBook();
            $favoriteBook->setUser($user);
            $favoriteBook->setBook($book);

            $this->entityManager->persist($favoriteBook);
            $this->entityManager->flush();

            return true;
        }
    }

    public function isFavorite(User $user, Book $book): bool
    {
        $favoriteBook = $this->favoriteBookRepository->findOneBy([
            'user' => $user,
            'book' => $book,
        ]);

        return $favoriteBook !== null;
    }

    public function getFavoritedUsers(Book $book): array
    {
        return $this->entityManager
            ->getRepository(FavoriteBook::class)
            ->findBy(['book' => $book]);
    }

}
