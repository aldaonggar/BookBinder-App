<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\FavoriteBook;
use App\Entity\User;
use App\Service\FavoriteBookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookController extends AbstractController
{
    private FavoriteBookService $favoriteBookService;

    public function __construct(FavoriteBookService $favoriteBookService)
    {
        $this->favoriteBookService = $favoriteBookService;
    }

    #[Route('/book/{id}', name: 'book_details', methods: ["GET"])]
    public function bookDetails(Book $book): Response
    {
        $favoritedUsers = $this->favoriteBookService->getFavoritedUsers($book);

        return $this->render('book.html.twig', [
            'book' => $book,
            'favoritedUsers' => $favoritedUsers,
        ]);
    }

    #[Route('/toggle-favorite/{id}', name: 'toggle_favorite', methods: ["POST"])]
    public function toggleFavorite(Book $book): JsonResponse
    {
        $user = $this->getUser();
        $isFavorite = $this->favoriteBookService->toggleFavorite($user, $book);

        return $this->json(['isFavorite' => $isFavorite]);
    }

    #[Route('/get-favorite-status/{id}', name: 'get_favorite_status', methods: ["GET"])]
    public function getFavoriteStatus(Book $book): JsonResponse
    {
        $user = $this->getUser();
        $isFavorite = $this->favoriteBookService->isFavorite($user, $book);

        return $this->json(['isFavorite' => $isFavorite]);
    }
}
