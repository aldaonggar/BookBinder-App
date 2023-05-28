<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\FavoriteBook;
use App\Entity\User;
use App\Service\FavoriteBookService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BookController extends AbstractController
{
    private $favoriteBookService;

    public function __construct(FavoriteBookService $favoriteBookService)
    {
        $this->favoriteBookService = $favoriteBookService;
    }

    /**
     * @Route("/book/{id}/add_to_favorites", name="book_add_to_favorites")
     */
    public function addToFavorites(Request $request, Book $book): RedirectResponse
    {
        // Get the currently logged in user
        /** @var User $user */
        $user = $this->getUser();

        // If there is no logged in user, deny access
        if (null === $user) {
            throw new AccessDeniedException('You must be logged in to add a book to favorites.');
        }

        // Create a new FavoriteBook entity
        $favoriteBook = new FavoriteBook();
        $favoriteBook->setBook($book);
        $favoriteBook->setUser($user);

        // Save the new FavoriteBook to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($favoriteBook);
        $entityManager->flush();

        // Add a flash message to the session to confirm the book was added to favorites
        $this->addFlash('success', 'Book added to favorites!');

        return $this->redirectToRoute('book', ['id' => $book->getId()]);
    }

    /**
     * @Route("/book/{id}/remove-favorite", name="book_remove_favorite")
     */
    public function removeFavorite($id, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $favorite = $entityManager->getRepository(FavoriteBook::class)->findOneBy([
            'user' => $user,
            'book' => $id,
        ]);

        if ($favorite) {
            $entityManager->remove($favorite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book', ['id' => $id]);
    }

    /**
     * @Route("/toggle-favorite/{id}", name="toggle_favorite", methods={"POST"})
     */
    public function toggleFavorite(Book $book): JsonResponse
    {
        // Get the currently logged-in user
        $user = $this->getUser();

        // Add or remove the book from the user's favorite list
        $isFavorite = $this->favoriteBookService->toggleFavorite($user, $book);

        // Return a JSON response
        return $this->json(['isFavorite' => $isFavorite]);
    }

}
