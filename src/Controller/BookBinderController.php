<?php

namespace App\Controller;

use App\Entity\Library;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Book;
use App\Form\SearchFormType;
use Doctrine\ORM\EntityManagerInterface;
//use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\ExtraClasses\BookGeneratorForTests;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

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

    public function renderBook(EntityManagerInterface $entityManager, int $id){
        $repository = $entityManager->getRepository(Book::class);
        $book = $repository->find($id);
        return $this->render('book.html.twig',[
            'book' => $book
        ]);
    }

    #[Route('/person', name: 'person')]
    public function renderPerson()
    {
        return $this->render('person.html.twig', ['entityManager' => $this->entityManager,]);
    }

    #[Route('/myprofile', name: 'myprofile')]
    public function renderMyProfile()
    {
        return $this->render('myprofile.html.twig', ['entityManager' => $this->entityManager,]);
    }

    #[Route('/usersettings', name: 'usersettings')]
    public function renderUserSettings()
    {
        $libraries = $this->entityManager->getRepository(Library::class)->findAll();

        return $this->render('usersettings.html.twig', [
            'libraries' => $libraries,]);
    }

    #[Route('/home', name: 'home')]
    public function renderHomepage()
    {
        return $this->render('homepage.html.twig');
    }

}
