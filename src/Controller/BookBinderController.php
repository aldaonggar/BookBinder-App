<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ExtraClasses\BookGeneratorForTests;
use Symfony\Component\HttpFoundation\Request;

class BookBinderController extends AbstractController
{
//    /**
//     * @Route("/booklist.html.twig")
//     */
// comment test
    public function renderBookList(EntityManagerInterface $entityManager, int $page, Request $request): Response{
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

        /*
         * This chunk is just to see if the book generator works fine
         *
         * $bookGenerator = new BookGeneratorForTests();
        $stringResponse = $bookGenerator->createStringResponse();
        return new Response($stringResponse);*/
    }

    public function renderBookListSearch(EntityManagerInterface $entityManager, string $searchTerm, Request $request): Response{
        $repository = $entityManager->getRepository(Book::class);
        $books = $repository->searchBooks($searchTerm);

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

    public function renderBook(EntityManagerInterface $entityManager, int $id){
        $repository = $entityManager->getRepository(Book::class);
        $book = $repository->find($id);
        return $this->render('book.html.twig',[
            'book' => $book
        ]);
    }

    public function renderPerson(){
        return $this->render('person.html.twig');
    }

    public function renderUserSettings(){
        return $this->render('usersettings.html.twig');
    }

    public function renderHomepage(){
        return $this->render('homepage.html.twig');
    }

    public function renderLogin(){
        return $this->render('login.html.twig');
    }

    public function renderRegister(){
        return $this->render('register.html.twig');
    }


}