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
        // Find the favorite book in the database
        $favoriteBook = $this->favoriteBookRepository->findOneBy([
            'user' => $user,
            'book' => $book,
        ]);

        if ($favoriteBook) {
            // If the book is already a favorite, remove it
            $this->entityManager->remove($favoriteBook);
            $this->entityManager->flush();

            return false;
        } else {
            // If the book is not a favorite, add it
            $favoriteBook = new FavoriteBook();
            $favoriteBook->setUser($user);
            $favoriteBook->setBook($book);

            $this->entityManager->persist($favoriteBook);
            $this->entityManager->flush();

            return true;
        }
    }
}
