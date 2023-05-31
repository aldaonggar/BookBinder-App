<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends AbstractController
{
    public function renderBookList(EntityManagerInterface $entityManager, int $page, Request $request): Response
    {
        $repository = $entityManager->getRepository(Book::class);
        $books = $repository->get21Books($page);
        $numberOfPages = ceil(($repository->getNumberOfBooks())/21);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('search', ['searchTerm' => $searchTerm]);
        }

        return $this->render('booklist.html.twig', [
            'bookArray'=>$books,
            'numberOfPages'=> $numberOfPages,
            'currentPage'=>$page,
            'form'=>$form->createView(),
            'search' => false,
        ]);
    }

    public function renderBookListSearch(EntityManagerInterface $entityManager, string $searchTerm, Request $request): Response
    {
        $repository = $entityManager->getRepository(Book::class);
        $books = $repository->searchBooksByAuthorAndTitle($searchTerm);

        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchTerm = $form->getData()['searchTerm'];

            return $this->redirectToRoute('search', ['searchTerm' => $searchTerm]);
        }

        return $this->render('booklist.html.twig', [
            'bookArray'=>$books,
            'form'=>$form->createView(),
            'search'=>true
        ]);
    }

    public function renderBook(EntityManagerInterface $entityManager, int $id):Response
    {
        $repository = $entityManager->getRepository(Book::class);
        $book = $repository->find($id);
        return $this->render('book.html.twig',[
            'book' => $book
        ]);
    }
}